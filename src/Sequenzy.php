<?php
/**
 * Sequenzy Client (Events + Transactional)
 *
 * This project supports two integration paths:
 * - Event automations: trigger Sequenzy sequences by calling the Events API.
 * - Transactional emails: send 1:1 emails via Transactional API (template slug).
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
        if (empty($this->config['enabled'])) {
            return false;
        }
        if (!empty($this->config['webhook_url'])) {
            return true;
        }
        return !empty($this->config['api_key']);
    }

    public function send(string $event, string $email, array $attributes = [], array $properties = []): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        if ($this->sendTransactionalForEvent($event, $email, $attributes, $properties)) {
            return true;
        }

        if (empty($this->config['webhook_url'])) {
            return $this->sendEventViaApi($event, $email, $attributes, $properties);
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

    private function sendEventViaApi(string $event, string $email, array $attributes, array $properties): bool
    {
        $apiUrl = rtrim((string) ($this->config['api_url'] ?? ''), '/');
        $apiKey = (string) ($this->config['api_key'] ?? '');
        if ($apiUrl === '' || $apiKey === '') {
            return false;
        }

        $properties['source'] = $properties['source'] ?? ($this->config['source'] ?? 'despertarespiral');

        if (!empty($attributes)) {
            $this->syncSubscriber($apiUrl, $apiKey, $email, $attributes);
        }

        $ok = $this->requestJson($apiUrl . '/subscribers/events', $apiKey, [
            'email' => $email,
            'event' => $event,
            'properties' => (object) $properties,
        ]);

        if (!$ok['success']) {
            error_log(sprintf(
                'Sequenzy event dispatch failed (event=%s, http=%s): %s',
                $event,
                $ok['http'],
                $ok['error']
            ));
            return false;
        }

        return true;
    }

    private function syncSubscriber(string $apiUrl, string $apiKey, string $email, array $attributes): void
    {
        $name = trim((string) ($attributes['name'] ?? ''));
        $firstName = '';
        $lastName = '';
        if ($name !== '') {
            $parts = preg_split('/\s+/', $name);
            $firstName = (string) ($parts[0] ?? '');
            if (count($parts) > 1) {
                array_shift($parts);
                $lastName = trim(implode(' ', $parts));
            }
        }

        $payload = [
            'email' => $email,
            'customAttributes' => (object) $attributes,
        ];
        if ($firstName !== '') {
            $payload['firstName'] = $firstName;
        }
        if ($lastName !== '') {
            $payload['lastName'] = $lastName;
        }

        $created = $this->requestJson($apiUrl . '/subscribers', $apiKey, $payload);
        if ($created['success']) {
            return;
        }

        if ((int) $created['http'] === 409) {
            $this->requestJson($apiUrl . '/subscribers/' . rawurlencode($email), $apiKey, $payload, 'PATCH');
        }
    }

    private function requestJson(string $url, string $apiKey, array $payload, string $method = 'POST'): array
    {
        $method = strtoupper($method);
        $body = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $apiKey,
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

        if ($response === false) {
            return [
                'success' => false,
                'http' => (int) $httpCode,
                'error' => $err !== '' ? $err : 'no response',
            ];
        }

        if ((int) $httpCode >= 400) {
            return [
                'success' => false,
                'http' => (int) $httpCode,
                'error' => is_string($response) ? substr($response, 0, 200) : 'http error',
            ];
        }

        return [
            'success' => true,
            'http' => (int) $httpCode,
            'error' => '',
        ];
    }

    private function sendTransactionalForEvent(string $event, string $email, array $attributes, array $properties): bool
    {
        if (empty($this->config['transactional_enabled'])) {
            return false;
        }

        $apiKey = (string) ($this->config['api_key'] ?? '');
        if ($apiKey === '') {
            return false;
        }

        $slugs = is_array($this->config['transactional_event_slugs'] ?? null) ? $this->config['transactional_event_slugs'] : [];
        $slug = (string) ($slugs[$event] ?? '');
        if ($slug === '') {
            return false;
        }

        $mode = (string) ($this->config['transactional_mode'] ?? 'template');
        $mode = $mode !== '' ? strtolower($mode) : 'template';

        $variables = array_merge($attributes, $properties);

        if ($mode === 'direct') {
            $subject = (string) ($variables['subject'] ?? '');
            $body = (string) ($variables['body'] ?? '');
            if ($subject === '' || $body === '') {
                return false;
            }
            return $this->sendTransactional([
                'to' => $email,
                'subject' => $subject,
                'body' => $body,
                'variables' => $variables,
            ]);
        }

        return $this->sendTransactional([
            'to' => $email,
            'slug' => $slug,
            'variables' => $variables,
        ]);
    }

    private function sendTransactional(array $payload): bool
    {
        $apiUrl = rtrim((string) ($this->config['api_url'] ?? ''), '/');
        $apiKey = (string) ($this->config['api_key'] ?? '');
        if ($apiUrl === '' || $apiKey === '') {
            return false;
        }

        $from = trim((string) ($this->config['transactional_from'] ?? ''));
        $replyTo = trim((string) ($this->config['transactional_reply_to'] ?? ''));
        if ($from !== '') {
            $payload['from'] = $from;
        }
        if ($replyTo !== '') {
            $payload['replyTo'] = $replyTo;
        }

        $body = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $ch = curl_init($apiUrl . '/transactional/send');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $apiKey,
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
                'Sequenzy transactional failed (http=%s): %s',
                $httpCode,
                $err ?: (is_string($response) ? substr($response, 0, 200) : 'no response')
            ));
            return false;
        }

        return true;
    }
}
