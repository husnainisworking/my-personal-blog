<?php

namespace App\Services\OAuth;

use RuntimeException;

class Jwt
{
    // JWT uses Base64Url encoding
    public static function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/'));
    }

    /* Decode JWT (NO signature verification here).
    *  Returns header + payload + the signed input string.
    */
    public static function decode(string $jwt): array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            throw new RuntimeException('Invalid JWT');
        }

        [$h, $p, $s] = $parts;

        $header = json_decode(self::base64UrlDecode($h), true);
        $payload = json_decode(self::base64UrlDecode($p), true);

        return [
            'header' => $header,
            'payload' => $payload,
            'signature_b64' => $s,
            'signed_part' => $h.'.'.$p,
        ];
    }

    public static function verifyRs256(string $signedPart, string $signatureB64, string $publicKeyPem): void
    {
        $signature = self::base64UrlDecode($signatureB64);
        $ok = openssl_verify($signedPart, $signature, $publicKeyPem, OPENSSL_ALGO_SHA256);
        if($ok !== 1) {
            throw new RunTimeException('Invalid JWT signature');
        }
    }

    public static function publicKeyPemFromJwk(array $jwk): string
    {
        if(! isset($jwk['n'], $jwk['e'])) {
            throw new RuntimeException('Invalid JWK');
        }

        $n = self::base64UrlDecode($jwk['n']);
        $e = self::base64UrlDecode($jwk['e']);

        $rsaPubKey = self::derSequence(
            self::derInteger($n).self::derInteger($e)
        );

        $algoId = self::derSequence(
            self::derObjectId("\x2a\x86\x48\x86\xf7\x0d\x01\x01\x01").self::derNull()
        );

        $spki = self::derSequence(
            $algoId.self::derBitString("\x00".$rsaPubKey) 
        );

        return "-----BEGIN PUBLIC KEY-----\n"
        .chunk_split(base64_encode($spki), 64, "\n")
        ."-----END PUBLIC KEY-----\n";
    }

    private static function derLength(int $length): string
    {
        if($length < 0x80) {
            return chr($length);
        }

        $bytes = ltrim(pack('N', $length), "\x00");
        return chr(0x80 | strlen($bytes)).$bytes;
    }

    private static function derInteger(string $data): string
    {
        if($data === '' || (ord($data[0]) & 0x80)) {
            $data = "\x00".$data;
        }

        return "\x02".self::derLength(strlen($data)).$data;
    }

    private static function derSequence(string $data): string
    {
        return "\x30".self::derLength(strlen($data)).$data;
    }

    private static function derBitString(string $data): string 
    {
        return "\x03".self::derLength(strlen($data)).$data;
    }

    private static function derNull(): string 
    {
        return "\x05\x00";
    }

    private static function derObjectId(string $oidBytes): string 
    {
        return "\x06".self::derLength(strlen($oidBytes)).$oidBytes;
    }
}
