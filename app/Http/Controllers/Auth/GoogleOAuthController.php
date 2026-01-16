<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OAuthAccount;
use App\Models\User;
use App\Services\OAuth\GoogleOAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered; 


class GoogleOAuthController extends Controller
{
    // Sends the user to Google's consent screen
    public function redirect(GoogleOAuth $google): RedirectResponse
    {
        $state = Str::random(40); // CSRF protection for OAuth
        $nonce = Str::random(40); // replay protection for id_token

        session([
            'oauth_google_state' => $state,
            'oauth_google_nonce' => $nonce,
        ]);

        return redirect()->away($google->authorizationUrl($state, $nonce));
    }

    // Handles the callback from Google
    public function callback(Request $request, GoogleOAuth $google): RedirectResponse
    {
        if ($request->input('error')) {
            return redirect()->route('login')->withErrors([
                'oauth' => $request->input('error_description') ?: $request->input('error'),
            ]);
        }

        // Validate state to prevent CSRF
        if ($request->input('state') !== session('oauth_google_state')) {
            abort(419, 'Invalid OAuth state');
        }

        if (! session('oauth_google_nonce')) {
            abort(419, 'Missing OAuth nonce');
        }



        $code = $request->input('code');
        if (! $code) {
            abort(400, 'Missing code');
        }

        // Exchange code -> tokens
        $token = $google->exchangeCode($code);

        $idToken = $token['id_token'] ?? null;
        if (! $idToken) {
            abort(400, 'Missing id_token');
        }

        // Validate token claims
        $claims = $google->parseIdToken($idToken, session('oauth_google_nonce'));

        session()->forget(['oauth_google_state', 'oauth_google_nonce']);


        $googleSub = $claims['sub']; // provider user id
        $email = $claims['email'] ?? null;
        $name = $claims['name'] ?? null;

         if($email && ($claims['email_verified'] ?? false) !== true ) {
            abort(403, 'Unverified Google email');
        }


                //
                //
        // 1) Find existing OAuth link
        $oauth = OAuthAccount::where('provider', 'google')
            ->where('provider_user_id', $googleSub)
            ->first();
        if ($oauth) {
            // Existing user - update their OAuth tokens
            $user = $oauth->user;

            $oauth->fill([
                'access_token' => $token['access_token'] ?? null,
                'refresh_token' => $token['refresh_token'] ?? $oauth->refresh_token,
                'id_token' => $idToken,
                'token_type' => $token['token_type'] ?? null,
                'scopes' => isset($token['scope']) ? (string) $token['scope'] : $oauth->scopes,
                'expires_at' => isset($token['expires_in']) ? now()->addSeconds((int) $token['expires_in']) : $oauth->expires_at,
                'last_used_at' => now(),
            ])->save();

            Auth::login($user, true);

            return redirect()->intended(route('home'));
        }
            else {
            // New user - create account automatically
            DB::beginTransaction();

            try {
                // Check if email already exists
                $existingUser = User::where('email', $email)->first();

                if ($existingUser) {
                    DB::rollBack();
                    return redirect()->route('login')->withErrors([
                        'email' => 'An account with this email already exists. Please login with password.'
                    ]);
                }

                // Create new user
                $user = User::create([
                    'name' => $name ?? 'Google User',
                    'email' => $email,
                    'email_verified_at' => now(), // Auto-verify Google emails
                    'password' => Hash::make(Str::random(32)), // Random secure password
                ]);

                    // Assign editor role (allows creating and publishing posts)
                     $user->assignRole('editor');

                // Create OAuth account link
                OAuthAccount::create([
                    'user_id' => $user->id,
                    'provider' => 'google',
                    'provider_user_id' => $googleSub,
                    'email' => $email,
                    'name' => $name,
                    'access_token' => $token['access_token'] ?? null,
                    'refresh_token' => $token['refresh_token'] ?? null,
                    'id_token' => $idToken,
                    'token_type' => $token['token_type'] ?? null,
                    'scopes' => isset($token['scope']) ? (string) $token['scope'] : null,
                    'expires_at' => isset($token['expires_in']) ? now()->addSeconds((int) $token['expires_in']) : null,
                    'last_used_at' => now(),
                ]);

                DB::commit();

                // Fire registration event (for listeners like sending welcome email)
                event(new Registered($user));

                // Log in the new user
                Auth::login($user, true);

                return redirect()->route('home')->with('success', 'Welcome! Your account has been created.');

            } catch(\Exception $e) {
                DB::rollBack();

                return redirect()->route('login')->withErrors([
                    'email' => 'Failed to create account. Please try again or contact support.'
                ]);
            }
        }


    }
}
