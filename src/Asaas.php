<?php
/**
 * Asaas Payment Gateway Integration
 *
 * Thin cURL client for docs.asaas.com. Supports:
 *   - Customer creation (required before any charge)
 *   - Payment (cobrança) creation with billingType UNDEFINED,
 *     which lets the customer choose PIX / credit card / boleto
 *     at the Asaas-hosted checkout page.
 *   - Payment retrieval
 *   - Webhook pre-shared token verification (asaas-access-token header)
 *
 * Configuration is loaded from config/asaas.php (which reads .env).
 */
class AsaasClient
{
    private string $apiKey;
    private string $baseUrl;
    private string $webhookToken;

    public function __construct()
    {
        $config = require BASE_PATH . '/config/asaas.php';
        $this->apiKey = (string) ($config['api_key'] ?? '');
        $this->baseUrl = rtrim((string) ($config['base_url'] ?? ''), '/');
        $this->webhookToken = (string) ($config['webhook_token'] ?? '');
    }

    /**
     * Create or upsert a customer in Asaas.
     * Returns the full response array (with 'id' on success) or null on failure.
     */
    public function createCustomer(string $name, string $email, ?string $cpfCnpj = null, ?string $phone = null): ?array
    {
        $payload = [
            'name' => $name,
            'email' => $email,
        ];
        if ($cpfCnpj !== null && $cpfCnpj !== '') {
            $payload['cpfCnpj'] = preg_replace('/\D+/', '', $cpfCnpj);
        }
        if ($phone !== null && $phone !== '') {
            $payload['mobilePhone'] = preg_replace('/\D+/', '', $phone);
        }

        return $this->request('POST', '/customers', $payload);
    }

    /**
     * Create a payment (cobrança).
     *
     * @param array $params Must contain: customer, value, dueDate, description, externalReference.
     *                      Optional: billingType (defaults to UNDEFINED), callback.
     */
    public function createPayment(array $params): ?array
    {
        $payload = array_merge([
            'billingType' => 'UNDEFINED',
        ], $params);

        return $this->request('POST', '/payments', $payload);
    }

    public function retrievePayment(string $paymentId): ?array
    {
        return $this->request('GET', '/payments/' . rawurlencode($paymentId));
    }

    /**
     * Status helpers (API truth) — keep webhook processing consistent across events.
     * Reference: Asaas payment status values (varies by billing type).
     */
    public function isPaidStatus(string $status): bool
    {
        $status = strtoupper(trim($status));
        return in_array($status, ['RECEIVED', 'CONFIRMED', 'RECEIVED_IN_CASH'], true);
    }

    public function isRefundedStatus(string $status): bool
    {
        $status = strtoupper(trim($status));
        return in_array($status, ['REFUNDED'], true);
    }

    public function isOverdueStatus(string $status): bool
    {
        $status = strtoupper(trim($status));
        return in_array($status, ['OVERDUE'], true);
    }

    /**
     * Verify the webhook pre-shared token using a timing-safe comparison.
     * Asaas sends the same authToken you configured in the dashboard via
     * the `asaas-access-token` HTTP header.
     */
    public function verifyWebhookToken(string $headerToken): bool
    {
        if ($this->webhookToken === '' || $headerToken === '') {
            return false;
        }
        return hash_equals($this->webhookToken, $headerToken);
    }

    private function request(string $method, string $endpoint, array $data = []): ?array
    {
        if ($this->apiKey === '' || $this->baseUrl === '') {
            error_log('Asaas: missing API key or base URL');
            return null;
        }

        $url = $this->baseUrl . $endpoint;

        $ch = curl_init();
        $headers = [
            'Content-Type: application/json',
            'access_token: ' . $this->apiKey,
            'User-Agent: DespertarEspiral/1.0',
        ];

        if ($method === 'GET') {
            if (!empty($data)) {
                $url .= '?' . http_build_query($data);
            }
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            error_log("Asaas cURL error ($method $endpoint): $curlError");
            return null;
        }

        $decoded = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300) {
            return is_array($decoded) ? $decoded : null;
        }

        $errorMsg = '';
        if (is_array($decoded) && isset($decoded['errors'][0]['description'])) {
            $errorMsg = $decoded['errors'][0]['description'];
        }
        error_log("Asaas API error ($httpCode) on $method $endpoint: $errorMsg | body=$response");
        return null;
    }
}
