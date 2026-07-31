<?php
/**
 * Testes do correlationID e da chave de idempotencia do webhook.
 *
 * O correlationID e o unico fio que liga a cobranca na Woovi ao pedido no nosso
 * banco. Se o round-trip quebrar, a cliente paga e nao recebe acesso.
 *
 * A chave de idempotencia decide se um evento e processado ou descartado como
 * repetido — se ela colidir demais, um evento legitimo some.
 */
declare(strict_types=1);

require_once BASE_PATH . '/controllers/CheckoutController.php';

test('correlationID: round-trip pedido -> correlationID -> pedido', function (): void {
    foreach ([1, 42, 999999] as $orderId) {
        $correlationId = CheckoutController::correlationIdForOrder($orderId);
        assertSame($orderId, CheckoutController::orderIdFromCorrelationId($correlationId));
    }
});

test('correlationID: formato esperado pela Woovi', function (): void {
    assertSame('DE-42', CheckoutController::correlationIdForOrder(42));
});

test('correlationID: ignora identificador que nao e nosso', function (): void {
    // Uma cobranca criada direto no painel da Woovi cai aqui. Nao pode virar
    // um id de pedido inventado.
    assertSame(null, CheckoutController::orderIdFromCorrelationId('cobranca-manual-do-painel'));
    assertSame(null, CheckoutController::orderIdFromCorrelationId(''));
    assertSame(null, CheckoutController::orderIdFromCorrelationId('DE-'));
    assertSame(null, CheckoutController::orderIdFromCorrelationId('DE-abc'));
    assertSame(null, CheckoutController::orderIdFromCorrelationId('XDE-42'));
    assertSame(null, CheckoutController::orderIdFromCorrelationId('DE-42-extra'));
});

test('idempotencia: mesmo evento da mesma cobranca gera a mesma chave', function (): void {
    $charge = ['identifier' => 'abc123', 'correlationID' => 'DE-42'];

    $a = CheckoutController::webhookEventKey('OPENPIX:CHARGE_COMPLETED', $charge, [], '{"payload":"a"}');
    $b = CheckoutController::webhookEventKey('OPENPIX:CHARGE_COMPLETED', $charge, [], '{"payload":"b"}');

    // Mesmo com payload diferente (a Woovi pode reenviar com timestamp novo),
    // a chave precisa bater para o reenvio ser reconhecido como repetido.
    assertSame($a, $b);
});

test('idempotencia: eventos diferentes da mesma cobranca geram chaves diferentes', function (): void {
    $charge = ['identifier' => 'abc123'];

    $completed = CheckoutController::webhookEventKey('OPENPIX:CHARGE_COMPLETED', $charge, [], '{}');
    $expired = CheckoutController::webhookEventKey('OPENPIX:CHARGE_EXPIRED', $charge, [], '{}');

    assertTrue($completed !== $expired, 'eventos distintos nao podem colidir');
});

test('idempotencia: dois reembolsos parciais da mesma cobranca NAO colidem', function (): void {
    // Sem o endToEndId na chave, o segundo reembolso seria descartado como
    // repetido e o pedido ficaria com o estado errado.
    $charge = ['identifier' => 'abc123'];

    $primeiro = CheckoutController::webhookEventKey(
        'OPENPIX:TRANSACTION_REFUND_RECEIVED',
        $charge,
        ['endToEndId' => 'E1111', 'partial' => true],
        '{}'
    );
    $segundo = CheckoutController::webhookEventKey(
        'OPENPIX:TRANSACTION_REFUND_RECEIVED',
        $charge,
        ['endToEndId' => 'E2222', 'partial' => true],
        '{}'
    );

    assertTrue($primeiro !== $segundo, 'reembolsos distintos precisam de chaves distintas');
});

test('idempotencia: sem cobranca no payload, cai no hash do corpo', function (): void {
    $key = CheckoutController::webhookEventKey('OPENPIX:CHARGE_COMPLETED', [], [], '{"sem":"charge"}');

    assertTrue($key !== '', 'deveria produzir alguma chave');
    // sha256 em hex tem 64 caracteres — cabe folgado no VARCHAR(80).
    assertSame(64, strlen($key));
});

test('idempotencia: a chave nunca estoura o VARCHAR(80) da coluna', function (): void {
    $charge = ['identifier' => str_repeat('x', 200)];
    $pix = ['endToEndId' => str_repeat('y', 200)];

    $key = CheckoutController::webhookEventKey('OPENPIX:TRANSACTION_REFUND_RECEIVED', $charge, $pix, '{}');

    assertTrue(strlen($key) <= 80, 'chave de ' . strlen($key) . ' caracteres estouraria a coluna');
});
