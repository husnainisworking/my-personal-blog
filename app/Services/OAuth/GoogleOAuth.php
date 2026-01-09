<?php

namespace App\Services\OAuth;

use Illuminate\Support\Facades\Http; // needed
use Illuminate\Support\Facades\Cache;
use RuntimeException;

class GoogleOAuth
{
    public function authorizationUrl(string $state, string $nonce): string
    {
        $cfg = config('oauth.google');

        // OpenID Connect: scope includes "openid"
        // state = CSRF protection
        // nonce = prevents replay attacks on id_token
        $query = http_build_query([
            'client_id' => $cfg['client_id'],
            'redirect_uri' => $cfg['redirect_uri'], // FIXED
            'response_type' => 'code',
            'scope' => 'openid email profile', // FIXED
            'state' => $state,
            'nonce' => $nonce,
            // These help get a refresh token (Google only returns refresh_token on first consent)
            'access_type' => 'offline',
            'prompt' => 'consent',
        ]);

        return 'https://accounts.google.com/o/oauth2/v2/auth?'.$query;

    }

    public function exchangeCode(string $code): array
    {
        $cfg = config('oauth.google');

        // Exchange authorization code for tokens
        $res = Http::asForm()->post('https://oauth2.googleapis.com/token', [

            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $cfg['client_id'],
            'client_secret' => $cfg['client_secret'],
            'redirect_uri' => $cfg['redirect_uri'],
        ]);

        if (! $res->ok()) {
            throw new RuntimeException('Google token exchange failed: '.$res->body());
        }

        return $res->json();
    }

    private function googleJwks(): array
    {
        return Cache::remember('google_oauth_jwks', now()->addHour(), function() {
            $res = Http::get('https://www.googleapis.com/oauth2/v3/certs');
            if(! $res->ok()){
                throw new RuntimeException('Failed to fetch Google JWKS');
            }
            $json = $res->json();
            return $json['keys'] ?? [];
        });

    }



    public function parseIdToken(string $idToken, string $expectedNonce): array
    {
        // NOTE : claim checks only (no signature verification yet)
        $decoded = Jwt::decode($idToken);
        $h = $decoded['header'];
        $p = $decoded['payload'];

        if(($h['alg'] ?? null)  !== 'RS256') {
            throw new RuntimeException('Invalid token algorithm');
        }

        $kid = $h['kid'] ?? null;
        if(! $kid) {
            throw new RuntimeException('Missing key id');
        }

        $jwk = collect($this->googleJwks())->firstWhere('kid', $kid);
        if(! $jwk) {
            throw new RuntimeException('Unknown key id');
        }

        $publicKeyPem = Jwt::publicKeyPemFromJwk($jwk);
        Jwt::verifyRs256($decoded['signed_part'], $decoded['signature_b64'], $publicKeyPem);

        if(($p['iss'] ?? null) !== 'https://accounts.google.com' && ($p['iss'] ?? null) !== 'accounts.google.com') {
            throw new RuntimeException('Invalid Google issuer');
        }

        if(($p['aud'] ?? null) !== config('oauth.google.client_id')) {
            throw new RuntimeException('Invalid Google audience');
        }

        if(($p['exp'] ?? 0) < time()) {
            throw new RuntimeException('Google token expired');
        }

        if(($p['nonce'] ?? null) !== $expectedNonce) {
            throw new RuntimeException('Invalid nonce');
        }

        return $p;
    }
}
