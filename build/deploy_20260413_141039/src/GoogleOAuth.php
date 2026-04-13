<?php

class GoogleOAuth
{
    public static function buildAuthUrl(array $config, string $state, string $nonce, string $codeVerifier): string
    {
        $challenge = self::base64urlEncode(hash('sha256', $codeVerifier, true));
        $params = [
            'client_id' => (string) ($config['client_id'] ?? ''),
            'redirect_uri' => (string) ($config['redirect_uri'] ?? ''),
            'response_type' => 'code',
            'scope' => (string) ($config['scopes'] ?? 'openid email profile'),
            'state' => $state,
            'nonce' => $nonce,
            'code_challenge' => $challenge,
            'code_challenge_method' => 'S256',
            'access_type' => 'online',
            'prompt' => 'select_account',
        ];

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }

    public static function exchangeCode(array $config, string $code, string $codeVerifier): ?array
    {
        $payload = [
            'code' => $code,
            'client_id' => (string) ($config['client_id'] ?? ''),
            'client_secret' => (string) ($config['client_secret'] ?? ''),
            'redirect_uri' => (string) ($config['redirect_uri'] ?? ''),
            'grant_type' => 'authorization_code',
            'code_verifier' => $codeVerifier,
        ];

        $response = self::postJson('https://oauth2.googleapis.com/token', $payload, 8);
        if (!$response || empty($response['id_token'])) {
            return null;
        }

        return $response;
    }

    public static function validateIdToken(array $config, string $idToken, string $nonce): ?array
    {
        $tokenInfoUrl = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($idToken);
        $info = self::getJson($tokenInfoUrl, 8);
        if (!$info) {
            return null;
        }

        $clientId = (string) ($config['client_id'] ?? '');
        if ($clientId === '' || (($info['aud'] ?? '') !== $clientId)) {
            return null;
        }

        $iss = (string) ($info['iss'] ?? '');
        $allowedIss = (array) ($config['issuer_allowed'] ?? []);
        if ($iss === '' || !in_array($iss, $allowedIss, true)) {
            return null;
        }

        $exp = isset($info['exp']) ? (int) $info['exp'] : 0;
        if ($exp > 0 && $exp < (time() - 60)) {
            return null;
        }

        $payload = self::decodeJwtPayload($idToken);
        if ($payload && isset($payload['nonce'])) {
            if (!hash_equals((string) $nonce, (string) $payload['nonce'])) {
                return null;
            }
        }

        $email = (string) ($info['email'] ?? '');
        $sub = (string) ($info['sub'] ?? '');
        if ($email === '' || $sub === '') {
            return null;
        }

        return [
            'sub' => $sub,
            'email' => $email,
            'email_verified' => (($info['email_verified'] ?? '') === 'true' || ($info['email_verified'] ?? false) === true),
            'name' => (string) ($info['name'] ?? ''),
            'picture' => (string) ($info['picture'] ?? ''),
            'given_name' => (string) ($info['given_name'] ?? ''),
            'family_name' => (string) ($info['family_name'] ?? ''),
        ];
    }

    private static function decodeJwtPayload(string $jwt): ?array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return null;
        }
        $json = self::base64urlDecode($parts[1]);
        if ($json === null) {
            return null;
        }
        $data = json_decode($json, true);
        return is_array($data) ? $data : null;
    }

    private static function getJson(string $url, int $timeoutSeconds): ?array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $timeoutSeconds,
            CURLOPT_CONNECTTIMEOUT => $timeoutSeconds,
            CURLOPT_FAILONERROR => false,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($body === false || $code < 200 || $code >= 300) {
            return null;
        }
        $data = json_decode($body, true);
        return is_array($data) ? $data : null;
    }

    private static function postJson(string $url, array $data, int $timeoutSeconds): ?array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $timeoutSeconds,
            CURLOPT_CONNECTTIMEOUT => $timeoutSeconds,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_FAILONERROR => false,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($body === false || $code < 200 || $code >= 300) {
            return null;
        }
        $decoded = json_decode($body, true);
        return is_array($decoded) ? $decoded : null;
    }

    private static function base64urlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64urlDecode(string $data): ?string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        $decoded = base64_decode(strtr($data, '-_', '+/'), true);
        return $decoded === false ? null : $decoded;
    }
}

