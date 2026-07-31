<?php
/**
 * Woovi (OpenPix) Payment Gateway Integration
 *
 * Thin cURL client for api.woovi.com. Substitui o AsaasClient.
 *
 * Suporta:
 *   - Criacao de cobranca PIX (POST /api/v1/charge)
 *   - Consulta de cobranca (GET /api/v1/charge/{id})
 *   - Validacao da assinatura do webhook (header x-webhook-signature)
 *
 * ATENCAO — diferencas em relacao ao Asaas:
 *   1. A Woovi e PIX-only no checkout. Nao ha cartao nem boleto aqui.
 *   2. O valor vai em CENTAVOS (int), nao em reais (float).
 *   3. O webhook NAO usa token pre-compartilhado. Cada requisicao traz o header
 *      `x-webhook-signature`, que e uma assinatura RSA-SHA256 feita com a chave
 *      PRIVADA da Woovi. Validamos com a chave PUBLICA dela (a mesma para todos
 *      os clientes) via openssl_verify. Assinatura invalida => 401.
 *   4. `correlationID` e o nosso identificador idempotente da cobranca; a Woovi
 *      devolve tambem um `identifier` proprio.
 *
 * Configuracao vem de config/woovi.php (que le o .env).
 */
class WooviClient
{
    private string $appId;
    private string $baseUrl;
    private string $webhookPublicKeyBase64;
    private int $timeout;

    /**
     * @param string|null $webhookPublicKeyBase64 Injetavel para teste. Em producao
     *                                            vem da config (chave publica da Woovi).
     */
    public function __construct(?string $webhookPublicKeyBase64 = null)
    {
        $config = require BASE_PATH . '/config/woovi.php';
        $this->appId = (string) ($config['app_id'] ?? '');
        $this->baseUrl = rtrim((string) ($config['base_url'] ?? ''), '/');
        $this->timeout = (int) ($config['timeout'] ?? 20);
        $this->webhookPublicKeyBase64 = $webhookPublicKeyBase64
            ?? (string) ($config['webhook_public_key'] ?? '');
    }

    public function isConfigured(): bool
    {
        return $this->appId !== '' && $this->baseUrl !== '';
    }

    /**
     * Cria uma cobranca PIX.
     *
     * @param int    $valueInCents  Valor em CENTAVOS (R$ 97,00 => 9700).
     * @param string $correlationId Identificador unico nosso (idempotente na Woovi).
     * @param string $comment       Descricao exibida ao pagador.
     * @param array  $customer      ['name' =>, 'email' =>, 'phone' =>, 'taxID' =>]
     *
     * @return array|null Resposta completa da API (com chave 'charge') ou null em falha.
     */
    public function createCharge(
        int $valueInCents,
        string $correlationId,
        string $comment = '',
        array $customer = []
    ): ?array {
        if ($valueInCents <= 0) {
            error_log('Woovi: createCharge recusado — valor deve ser positivo em centavos');
            return null;
        }
        if ($correlationId === '') {
            error_log('Woovi: createCharge recusado — correlationID vazio');
            return null;
        }

        $payload = [
            'correlationID' => $correlationId,
            'value' => $valueInCents,
        ];

        if ($comment !== '') {
            // A Woovi limita o comentario; cortamos para nao estourar.
            $payload['comment'] = mb_substr($comment, 0, 140);
        }

        $customerPayload = $this->buildCustomer($customer);
        if ($customerPayload !== []) {
            $payload['customer'] = $customerPayload;
        }

        return $this->request('POST', '/charge', $payload);
    }

    /**
     * Consulta uma cobranca pelo correlationID (ou pelo identifier da Woovi).
     */
    public function getCharge(string $chargeId): ?array
    {
        if ($chargeId === '') {
            return null;
        }
        return $this->request('GET', '/charge/' . rawurlencode($chargeId));
    }

    /**
     * Valida a assinatura do webhook.
     *
     * A Woovi assina o corpo BRUTO da requisicao com a chave privada dela; validamos
     * com a chave publica (RSA + SHA-256). O corpo precisa ser exatamente o recebido
     * (file_get_contents('php://input')) — qualquer re-serializacao quebra a assinatura.
     *
     * @param string $rawPayload Corpo bruto, sem nenhum tratamento.
     * @param string $signature  Conteudo do header x-webhook-signature (base64).
     */
    public function verifyWebhookSignature(string $rawPayload, string $signature): bool
    {
        if ($rawPayload === '' || $signature === '') {
            return false;
        }
        if ($this->webhookPublicKeyBase64 === '') {
            error_log('Woovi: chave publica do webhook ausente — assinatura nao pode ser validada');
            return false;
        }

        $publicKeyPem = base64_decode($this->webhookPublicKeyBase64, true);
        if ($publicKeyPem === false || $publicKeyPem === '') {
            error_log('Woovi: chave publica do webhook invalida (base64)');
            return false;
        }

        $decodedSignature = base64_decode($signature, true);
        if ($decodedSignature === false || $decodedSignature === '') {
            return false;
        }

        $publicKey = openssl_pkey_get_public($publicKeyPem);
        if ($publicKey === false) {
            error_log('Woovi: chave publica do webhook nao pode ser lida pelo OpenSSL');
            return false;
        }

        $result = openssl_verify($rawPayload, $decodedSignature, $publicKey, 'sha256WithRSAEncryption');

        // openssl_verify: 1 = valida, 0 = invalida, -1 = erro.
        return $result === 1;
    }

    /**
     * Normaliza os dados do cliente para o formato da Woovi.
     * Campos vazios sao omitidos — a API rejeita string vazia em alguns deles.
     */
    private function buildCustomer(array $customer): array
    {
        $out = [];
        foreach (['name', 'email', 'phone', 'taxID'] as $field) {
            $value = trim((string) ($customer[$field] ?? ''));
            if ($value !== '') {
                $out[$field] = $value;
            }
        }
        return $out;
    }

    /**
     * @return array|null Corpo decodificado em caso de 2xx; null caso contrario.
     */
    private function request(string $method, string $endpoint, array $data = []): ?array
    {
        if (!$this->isConfigured()) {
            error_log('Woovi: WOOVI_APP_ID ausente — requisicao abortada');
            return null;
        }

        $ch = curl_init($this->baseUrl . $endpoint);
        if ($ch === false) {
            error_log('Woovi: falha ao inicializar cURL');
            return null;
        }

        $headers = [
            'Authorization: ' . $this->appId, // sem "Bearer" — a Woovi usa o AppID cru
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 10,
        ]);

        if ($method !== 'GET' && $data !== []) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
        }

        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            error_log("Woovi: erro de rede em {$method} {$endpoint}: {$curlError}");
            return null;
        }

        $decoded = json_decode((string) $response, true);

        if ($httpCode < 200 || $httpCode >= 300) {
            // Nunca logar o corpo inteiro: pode conter dados do pagador.
            $errorMsg = is_array($decoded) ? ($decoded['error'] ?? $decoded['message'] ?? '') : '';
            error_log("Woovi: HTTP {$httpCode} em {$method} {$endpoint} — " . (string) $errorMsg);
            return null;
        }

        return is_array($decoded) ? $decoded : null;
    }
}
