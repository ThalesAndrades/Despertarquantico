<?php
/**
 * Stripe Integration via cURL (no SDK required)
 */
class StripeClient
{
    private string $secretKey;
    private string $webhookSecret;
    private string $currency;

    public function __construct()
    {
        $config = require BASE_PATH . '/config/stripe.php';
        $this->secretKey = $config['secret_key'];
        $this->webhookSecret = $config['webhook_secret'];
        $this->currency = $config['currency'];
    }

    public function createCheckoutSession(
        string $productName,
        int $amountCents,
        string $customerEmail,
        string $successUrl,
        string $cancelUrl,
        array $metadata = []
    ): ?array {
        $data = [
            'payment_method_types[]' => 'card',
            'mode' => 'payment',
            'customer_email' => $customerEmail,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'line_items[0][price_data][currency]' => $this->currency,
            'line_items[0][price_data][product_data][name]' => $productName,
            'line_items[0][price_data][unit_amount]' => $amountCents,
            'line_items[0][quantity]' => 1,
        ];

        foreach ($metadata as $key => $value) {
            $data["metadata[$key]"] = $value;
        }

        return $this->request('POST', '/v1/checkout/sessions', $data);
    }

    public function retrieveSession(string $sessionId): ?array
    {
        return $this->request('GET', '/v1/checkout/sessions/' . $sessionId);
    }

    public function verifyWebhookSignature(string $payload, string $sigHeader): ?array
    {
        $elements = [];
        foreach (explode(',', $sigHeader) as $part) {
            [$key, $value] = explode('=', trim($part), 2);
            $elements[$key] = $value;
        }

        $timestamp = $elements['t'] ?? '';
        $signature = $elements['v1'] ?? '';

        if (empty($timestamp) || empty($signature)) {
            return null;
        }

        // Reject timestamps older than 5 minutes
        if (abs(time() - (int) $timestamp) > 300) {
            return null;
        }

        $signedPayload = $timestamp . '.' . $payload;
        $expected = hash_hmac('sha256', $signedPayload, $this->webhookSecret);

        if (!hash_equals($expected, $signature)) {
            return null;
        }

        return json_decode($payload, true);
    }

    private function request(string $method, string $endpoint, array $data = []): ?array
    {
        $url = 'https://api.stripe.com' . $endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . ($method === 'GET' && $data ? '?' . http_build_query($data) : ''));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->secretKey . ':');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300 && $response) {
            return json_decode($response, true);
        }

        error_log("Stripe API error ($httpCode): $response");
        return null;
    }
}
