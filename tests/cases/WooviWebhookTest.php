<?php
/**
 * Testes da validacao de assinatura do webhook da Woovi.
 *
 * Este e o ponto mais critico da integracao: quem passa por aqui consegue marcar
 * um pedido como PAGO e liberar acesso ao curso. Um bug aqui e prejuizo direto.
 *
 * Como testamos sem a chave privada da Woovi: geramos um par RSA proprio no teste
 * e injetamos a chave publica no WooviClient (o construtor aceita a chave). Assim
 * exercitamos o mesmo caminho de codigo (openssl_verify + RSA-SHA256 + base64) que
 * roda em producao, sem depender de rede nem de segredo real.
 */
declare(strict_types=1);

require_once BASE_PATH . '/src/Env.php';
require_once BASE_PATH . '/src/Woovi.php';

/**
 * Gera um par de chaves RSA para o teste.
 *
 * @return array{0: OpenSSLAsymmetricKey, 1: string} [chavePrivada, chavePublicaBase64]
 */
function makeTestKeyPair(): array
{
    $resource = openssl_pkey_new([
        'private_key_bits' => 2048,
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
    ]);

    if ($resource === false) {
        throw new RuntimeException('nao foi possivel gerar par de chaves de teste');
    }

    $details = openssl_pkey_get_details($resource);
    if ($details === false || !isset($details['key'])) {
        throw new RuntimeException('nao foi possivel extrair a chave publica de teste');
    }

    return [$resource, base64_encode($details['key'])];
}

function signWithTestKey(string $payload, $privateKey): string
{
    $signature = '';
    openssl_sign($payload, $signature, $privateKey, 'sha256WithRSAEncryption');
    return base64_encode($signature);
}

// -------------------------------------------------------------------------

test('webhook: aceita assinatura valida do payload exato', function (): void {
    [$privateKey, $publicKeyBase64] = makeTestKeyPair();
    $client = new WooviClient($publicKeyBase64);

    $payload = '{"event":"OPENPIX:CHARGE_COMPLETED","charge":{"correlationID":"pedido-42","status":"COMPLETED"}}';
    $signature = signWithTestKey($payload, $privateKey);

    assertTrue(
        $client->verifyWebhookSignature($payload, $signature),
        'assinatura legitima deveria ser aceita'
    );
});

test('webhook: REJEITA payload adulterado com assinatura valida do original', function (): void {
    [$privateKey, $publicKeyBase64] = makeTestKeyPair();
    $client = new WooviClient($publicKeyBase64);

    $original = '{"event":"OPENPIX:CHARGE_COMPLETED","charge":{"correlationID":"pedido-42","value":9700}}';
    $signature = signWithTestKey($original, $privateKey);

    // Atacante troca o pedido alvo mantendo a assinatura do payload original.
    $adulterado = '{"event":"OPENPIX:CHARGE_COMPLETED","charge":{"correlationID":"pedido-99","value":9700}}';

    assertFalse(
        $client->verifyWebhookSignature($adulterado, $signature),
        'payload adulterado NAO pode ser aceito'
    );
});

test('webhook: rejeita assinatura de outra chave (webhook forjado)', function (): void {
    [, $publicKeyBase64] = makeTestKeyPair();
    [$outraPrivada] = makeTestKeyPair();
    $client = new WooviClient($publicKeyBase64);

    $payload = '{"event":"OPENPIX:CHARGE_COMPLETED","charge":{"correlationID":"pedido-42"}}';
    $signature = signWithTestKey($payload, $outraPrivada);

    assertFalse(
        $client->verifyWebhookSignature($payload, $signature),
        'assinatura de chave estranha NAO pode ser aceita'
    );
});

test('webhook: rejeita assinatura vazia', function (): void {
    [, $publicKeyBase64] = makeTestKeyPair();
    $client = new WooviClient($publicKeyBase64);

    assertFalse($client->verifyWebhookSignature('{"event":"x"}', ''));
});

test('webhook: rejeita payload vazio', function (): void {
    [$privateKey, $publicKeyBase64] = makeTestKeyPair();
    $client = new WooviClient($publicKeyBase64);

    assertFalse($client->verifyWebhookSignature('', signWithTestKey('', $privateKey)));
});

test('webhook: rejeita assinatura que nao e base64 valido', function (): void {
    [, $publicKeyBase64] = makeTestKeyPair();
    $client = new WooviClient($publicKeyBase64);

    assertFalse($client->verifyWebhookSignature('{"event":"x"}', '!!!nao-e-base64!!!'));
});

test('webhook: rejeita tudo quando a chave publica esta ausente', function (): void {
    // Cenario real: WOOVI_WEBHOOK_PUBLIC_KEY apagada por engano no .env.
    // Deve falhar FECHADO (negar), nunca aberto.
    $client = new WooviClient('');

    assertFalse(
        $client->verifyWebhookSignature('{"event":"x"}', base64_encode('qualquer-coisa')),
        'sem chave publica o webhook deve falhar fechado'
    );
});

test('webhook: a chave publica padrao da Woovi e legivel pelo OpenSSL', function (): void {
    // Protege contra alguem quebrar a constante em config/woovi.php.
    $config = require BASE_PATH . '/config/woovi.php';
    $pem = base64_decode((string) $config['webhook_public_key'], true);

    assertTrue($pem !== false && $pem !== '', 'a chave publica deveria decodificar de base64');
    assertStringContains('BEGIN PUBLIC KEY', (string) $pem);
    assertTrue(
        openssl_pkey_get_public((string) $pem) !== false,
        'a chave publica padrao deveria ser carregavel pelo OpenSSL'
    );
});
