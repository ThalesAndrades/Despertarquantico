<?php
/**
 * Sequenzy Webhook Client
 *
 * Fire-and-forget POST to the workspace webhook URL defined in .env.
 * The remote end handles retries/backoff, so this class intentionally
 * uses a very short timeout and never throws — failures are logged and
 * the caller continues unaffected.
 *
 * Payload shape:
 * {
 *   "event": "user.registered",
 *   "email": "user@example.com",
 *   "attributes": { "name": "...", ... },
 *   "properties": { "product_slug": "...", ... },
 *   "timestamp": "2026-04-10T12:34:56+00:00",
 *   "source": "despertarespiral"
 * }
 */
class SequenzyClient
{
    private array $config;

    public function __construct()
    {
        $this->config = require BASE_PATH . '/config/sequenzy.php';
    }

    public function isEnabled(): bool
    {
        return !empty($this->config['enabled']) && !empty($this->config['webhook_url']);
    }

    public function send(string $event, string $email, array $attributes = [], array $properties = []): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $payload = [
            'event' => $event,
            'email' => $email,
            'attributes' => (object) $attributes,
            'properties' => (object) $properties,
            'timestamp' => date('c'),
            'source' => $this->config['source'] ?? 'despertarespiral',
        ];

        $body = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $ch = curl_init($this->config['webhook_url']);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                'User-Agent: DespertarEspiral/1.0',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT => (int) ($this->config['timeout'] ?? 3),
            CURLOPT_FAILONERROR => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($response === false || $httpCode >= 400) {
            error_log(sprintf(
                'Sequenzy dispatch failed (event=%s, http=%s): %s',
                $event,
                $httpCode,
                $err ?: (is_string($response) ? substr($response, 0, 200) : 'no response')
            ));
            return false;
        }

        return true;
    }
}
