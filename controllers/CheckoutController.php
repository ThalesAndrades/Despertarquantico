<?php

class CheckoutController
{
    public function createPost(string $slug): void
    {
        CSRF::check();
        $this->create($slug);
    }

    public function create(string $slug): void
    {
        $product = Database::fetch(
            "SELECT * FROM products WHERE slug = ? AND is_active = 1",
            [$slug]
        );
        if (!$product) {
            flash('error', 'Produto não encontrado.');
            redirect('');
            return;
        }

        $email = '';
        $name = '';
        if (isLoggedIn()) {
            $user = currentUser();
            $email = $user['email'];
            $name = $user['name'];
        }

        $submittedEmail = trim($_POST['email'] ?? $_GET['email'] ?? '');
        $submittedName = trim($_POST['name'] ?? '');
        $submittedCpf = trim($_POST['cpf_cnpj'] ?? '');

        if (empty($email) && empty($submittedEmail)) {
            view('checkout/email', compact('product'));
            return;
        }

        $email = $email ?: $submittedEmail;
        $name = $name ?: ($submittedName ?: 'Cliente Despertar Espiral');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'E-mail inválido.');
            redirect('checkout/' . $slug);
            return;
        }

        $userId = $_SESSION['user_id'] ?? null;
        closeSession();

        require_once BASE_PATH . '/src/Woovi.php';
        $woovi = new WooviClient();

        if (!$woovi->isConfigured()) {
            error_log('Checkout: WOOVI_APP_ID ausente — cobranca nao pode ser criada');
            flash('error', 'Pagamento temporariamente indisponível. Tente novamente em instantes.');
            redirect('checkout/' . $slug);
            return;
        }

        // Cria o pedido primeiro para termos um id local que vira o correlationID
        // da Woovi (identificador idempotente da cobranca do lado deles).
        $placeholder = 'pending_' . bin2hex(random_bytes(12));
        $orderId = Database::insert(
            "INSERT INTO orders (user_id, product_id, provider, provider_charge_id, customer_email, customer_name, amount, currency, status, payment_method)
             VALUES (?, ?, 'woovi', ?, ?, ?, ?, 'brl', 'pending', 'pix')",
            [
                $userId,
                $product['id'],
                $placeholder,
                $email,
                $name,
                $product['price'],
            ]
        );

        $correlationId = self::correlationIdForOrder((int) $orderId);

        // A Woovi trabalha em CENTAVOS. Arredondar antes de converter evita
        // perder um centavo por imprecisao de float (ex.: 97.00 -> 9699).
        $valueInCents = (int) round(((float) $product['price']) * 100);

        $response = $woovi->createCharge(
            $valueInCents,
            $correlationId,
            $product['title'] . ' - Despertar Espiral',
            [
                'name' => $name,
                'email' => $email,
                'taxID' => $submittedCpf ?: '',
            ]
        );

        $charge = is_array($response) ? ($response['charge'] ?? null) : null;
        $paymentUrl = is_array($charge) ? (string) ($charge['paymentLinkUrl'] ?? '') : '';
        $chargeId = is_array($charge) ? (string) ($charge['identifier'] ?? '') : '';

        if (!$charge || $paymentUrl === '') {
            // Desfaz o pedido pendente para nao deixar orfao no banco.
            Database::query("DELETE FROM orders WHERE id = ?", [$orderId]);
            flash('error', 'Erro ao gerar o PIX. Tente novamente.');
            redirect('checkout/' . $slug);
            return;
        }

        Database::query(
            "UPDATE orders
             SET provider_charge_id = ?, provider_correlation_id = ?, provider_payment_url = ?, brcode = ?
             WHERE id = ?",
            [
                $chargeId !== '' ? $chargeId : $correlationId,
                $correlationId,
                $paymentUrl,
                (string) ($charge['brCode'] ?? ''),
                $orderId,
            ]
        );

        EventDispatcher::dispatch('checkout.started', [
            'email' => $email,
            'attributes' => ['name' => $name],
            'properties' => [
                'product_slug' => $product['slug'],
                'product_title' => $product['title'],
                'amount' => (float) $product['price'],
                'order_id' => (int) $orderId,
                'checkout_url' => APP_URL . '/checkout/' . $product['slug'],
                'invoice_url' => $paymentUrl,
                'payment_provider' => 'woovi',
                'woovi_charge_id' => $chargeId,
            ],
        ]);

        header('Location: ' . $paymentUrl);
        exit;
    }

    public function success(): void
    {
        $orderId = (int) ($_GET['order'] ?? 0);
        $order = null;
        if ($orderId > 0) {
            $candidate = Database::fetch(
                "SELECT o.*, p.title as product_title
                 FROM orders o JOIN products p ON o.product_id = p.id
                 WHERE o.id = ?",
                [$orderId]
            );

            // Only surface order details to a viewer that plausibly owns it.
            // Anyone can guess sequential order ids; without this check,
            // the page would leak the product title and amount.
            if ($candidate) {
                $sessionUserId = (int) ($_SESSION['user_id'] ?? 0);
                $sessionEmail = strtolower((string) ($_SESSION['user_email'] ?? ''));
                $orderUserId = (int) ($candidate['user_id'] ?? 0);
                $orderEmail = strtolower((string) ($candidate['customer_email'] ?? ''));

                $ownsBySession = $sessionUserId > 0 && $orderUserId === $sessionUserId;
                $ownsByEmail = $sessionEmail !== '' && $sessionEmail === $orderEmail;

                if ($ownsBySession || $ownsByEmail) {
                    $order = $candidate;
                }
            }
        }

        view('checkout/success', compact('order'));
    }

    public function cancel(): void
    {
        view('checkout/cancel');
    }

    /**
     * correlationID enviado a Woovi. Deterministico a partir do id do pedido,
     * para que o webhook consiga achar o pedido mesmo se a resposta da criacao
     * da cobranca se perder no meio do caminho.
     */
    public static function correlationIdForOrder(int $orderId): string
    {
        return 'DE-' . $orderId;
    }

    /**
     * Extrai o id do pedido de um correlationID. Retorna null se nao for nosso.
     */
    public static function orderIdFromCorrelationId(string $correlationId): ?int
    {
        if (preg_match('/^DE-(\d+)$/', $correlationId, $m) !== 1) {
            return null;
        }
        return (int) $m[1];
    }

    /**
     * Chave de idempotencia do evento. A Woovi nao manda id de evento, entao
     * derivamos de (tipo + cobranca + movimento pix). Sem o endToEndId, dois
     * reembolsos parciais da mesma cobranca colidiriam e o segundo seria
     * silenciosamente descartado.
     */
    public static function webhookEventKey(string $eventType, array $charge, array $pix, string $rawPayload): string
    {
        $chargeRef = (string) ($charge['identifier'] ?? $charge['correlationID'] ?? '');
        if ($chargeRef === '') {
            return substr(hash('sha256', $rawPayload), 0, 80);
        }

        $key = $eventType . ':' . $chargeRef;

        $movement = (string) ($pix['endToEndId'] ?? $pix['transactionID'] ?? $pix['time'] ?? '');
        if ($movement !== '') {
            $key .= ':' . $movement;
        }

        // A coluna event_key e VARCHAR(80).
        return strlen($key) > 80 ? substr(hash('sha256', $key), 0, 80) : $key;
    }

    /**
     * Webhook da Woovi — publico, stateless, sem sessao/CSRF.
     *
     * Autenticado pela assinatura RSA-SHA256 no header `x-webhook-signature`,
     * validada com a chave publica da Woovi. Diferente do Asaas, NAO ha token
     * pre-compartilhado.
     */
    public function webhook(): void
    {
        $payload = file_get_contents('php://input');
        $signature = (string) ($_SERVER['HTTP_X_WEBHOOK_SIGNATURE'] ?? '');

        if (empty($payload)) {
            http_response_code(400);
            echo json_encode(['error' => 'Empty payload']);
            return;
        }

        require_once BASE_PATH . '/src/Woovi.php';
        $woovi = new WooviClient();

        if (!$woovi->verifyWebhookSignature((string) $payload, $signature)) {
            error_log('Woovi webhook rejeitado: assinatura x-webhook-signature invalida');
            http_response_code(401);
            echo json_encode(['error' => 'Invalid signature']);
            return;
        }

        $event = json_decode((string) $payload, true);
        if (!is_array($event) || empty($event['event'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Malformed payload']);
            return;
        }

        $eventType = (string) $event['event'];
        $charge = is_array($event['charge'] ?? null) ? $event['charge'] : [];
        $pix = is_array($event['pix'] ?? null) ? $event['pix'] : [];

        $correlationId = (string) ($charge['correlationID'] ?? '');
        $chargeIdentifier = (string) ($charge['identifier'] ?? '');

        if ($correlationId === '' && $chargeIdentifier === '') {
            // Ex.: webhook de teste da Woovi, que nao traz cobranca.
            http_response_code(200);
            echo json_encode(['received' => true, 'ignored' => 'no charge in payload']);
            return;
        }

        // ---- Inbox de idempotencia ----
        $provider = 'woovi';
        $eventKey = self::webhookEventKey($eventType, $charge, $pix, (string) $payload);
        $payloadHash = hash('sha256', (string) $payload);
        $externalOrderId = self::orderIdFromCorrelationId($correlationId);

        try {
            Database::query(
                "INSERT INTO webhook_events (provider, event_key, event_type, payment_id, order_id, payload_hash, payload_json, attempts)
                 VALUES (?, ?, ?, ?, ?, ?, ?, 1)
                 ON DUPLICATE KEY UPDATE
                    attempts = attempts + 1,
                    event_type = VALUES(event_type),
                    payment_id = VALUES(payment_id),
                    order_id = COALESCE(VALUES(order_id), order_id),
                    payload_hash = VALUES(payload_hash),
                    payload_json = VALUES(payload_json),
                    received_at = NOW()",
                [
                    $provider,
                    $eventKey,
                    $eventType,
                    $chargeIdentifier !== '' ? $chargeIdentifier : $correlationId,
                    $externalOrderId,
                    $payloadHash,
                    $payload,
                ]
            );
        } catch (Throwable $e) {
            // Nunca falhar o webhook por causa do inbox — processar mesmo assim,
            // para nao perder pedido pago.
            error_log('Woovi webhook: falha ao gravar inbox webhook_events: ' . $e->getMessage());
        }

        $inboxRow = Database::fetch(
            "SELECT id, processed_at FROM webhook_events WHERE provider = ? AND event_key = ?",
            [$provider, $eventKey]
        );
        if ($inboxRow && !empty($inboxRow['processed_at'])) {
            http_response_code(200);
            echo json_encode(['received' => true, 'idempotent' => true]);
            return;
        }

        // ---- Localiza o pedido ----
        $order = null;
        if ($externalOrderId !== null) {
            $order = Database::fetch(
                "SELECT o.*, p.title as product_title, p.slug as product_slug
                 FROM orders o JOIN products p ON o.product_id = p.id
                 WHERE o.id = ?",
                [$externalOrderId]
            );
        }
        if (!$order && $chargeIdentifier !== '') {
            $order = Database::fetch(
                "SELECT o.*, p.title as product_title, p.slug as product_slug
                 FROM orders o JOIN products p ON o.product_id = p.id
                 WHERE o.provider_charge_id = ?",
                [$chargeIdentifier]
            );
        }

        if (!$order) {
            error_log("Woovi webhook: pedido nao encontrado para {$correlationId} / {$chargeIdentifier} (evento {$eventType})");
            http_response_code(200);
            echo json_encode(['received' => true, 'ignored' => 'order not found']);
            return;
        }

        // ---- Revalida na API antes de mexer em estado local ----
        // O payload assinado ja e confiavel, mas consultar a API protege contra
        // replay de um evento antigo depois de um estorno.
        $lookupId = $correlationId !== '' ? $correlationId : $chargeIdentifier;
        $apiResponse = $woovi->getCharge($lookupId);
        $apiCharge = is_array($apiResponse) ? ($apiResponse['charge'] ?? $apiResponse) : null;

        if (!is_array($apiCharge) || empty($apiCharge['status'])) {
            // 503 faz a Woovi reenviar; nao marcamos processed_at para o retry rodar.
            if ($inboxRow && !empty($inboxRow['id'])) {
                Database::query(
                    "UPDATE webhook_events SET error_message = ?, process_result = ? WHERE id = ?",
                    ['api_retrieve_failed', 'retry', (int) $inboxRow['id']]
                );
            }
            http_response_code(503);
            echo json_encode(['error' => 'Could not validate charge']);
            return;
        }

        // Confere o valor (em centavos) contra o pedido local.
        $apiValueCents = isset($apiCharge['value']) ? (int) $apiCharge['value'] : null;
        if ($apiValueCents !== null) {
            $orderValueCents = (int) round(((float) $order['amount']) * 100);
            if ($apiValueCents !== $orderValueCents) {
                error_log("Woovi webhook: valor divergente no pedido {$order['id']} (api={$apiValueCents}, local={$orderValueCents})");
                if ($inboxRow && !empty($inboxRow['id'])) {
                    Database::query(
                        "UPDATE webhook_events SET processed_at = NOW(), process_result = ?, error_message = ? WHERE id = ?",
                        ['ignored', 'value_mismatch', (int) $inboxRow['id']]
                    );
                }
                http_response_code(200);
                echo json_encode(['received' => true, 'ignored' => 'value mismatch']);
                return;
            }
        }

        $apiStatus = strtoupper((string) ($apiCharge['status'] ?? ''));

        switch ($eventType) {
            case 'OPENPIX:CHARGE_COMPLETED':
            case 'OPENPIX:TRANSACTION_RECEIVED':
                if ($apiStatus === 'COMPLETED') {
                    $this->handlePaymentPaid($order, 'pix', $eventType);
                } else {
                    error_log("Woovi webhook: evento de pagamento {$eventType} mas status da API={$apiStatus} para {$lookupId}");
                }
                break;

            case 'OPENPIX:CHARGE_EXPIRED':
                if ($apiStatus !== 'EXPIRED') {
                    error_log("Woovi webhook: evento de expiracao mas status da API={$apiStatus} para {$lookupId}");
                    break;
                }
                Database::query(
                    "UPDATE orders SET status = 'failed', provider_event = ? WHERE id = ? AND status <> 'paid'",
                    [$eventType, $order['id']]
                );
                EventDispatcher::dispatch('order.overdue', [
                    'email' => $order['customer_email'],
                    'properties' => [
                        'order_id' => (int) $order['id'],
                        'product_slug' => $order['product_slug'],
                        'invoice_url' => $order['provider_payment_url'] ?? null,
                        'checkout_url' => APP_URL . '/checkout/' . $order['product_slug'],
                    ],
                ]);
                break;

            case 'OPENPIX:TRANSACTION_REFUND_RECEIVED':
                // Reembolso parcial NAO revoga acesso — so o total.
                $isPartial = (bool) ($pix['partial'] ?? false);
                if ($isPartial) {
                    Database::query(
                        "UPDATE orders SET provider_event = ? WHERE id = ?",
                        [$eventType, $order['id']]
                    );
                    error_log("Woovi webhook: reembolso PARCIAL no pedido {$order['id']} — acesso mantido");
                    break;
                }

                Database::query(
                    "UPDATE orders SET status = 'refunded', provider_event = ? WHERE id = ?",
                    [$eventType, $order['id']]
                );
                if (!empty($order['user_id'])) {
                    Database::query(
                        "DELETE FROM user_products WHERE user_id = ? AND product_id = ?",
                        [$order['user_id'], $order['product_id']]
                    );
                }
                EventDispatcher::dispatch('order.refunded', [
                    'email' => $order['customer_email'],
                    'properties' => [
                        'order_id' => (int) $order['id'],
                        'product_slug' => $order['product_slug'],
                        'product_name' => $order['product_title'],
                        'invoice_url' => $order['provider_payment_url'] ?? null,
                    ],
                ]);
                break;

            default:
                // Registra eventos nao tratados para visibilidade, sem falhar.
                error_log("Woovi webhook: evento nao tratado {$eventType} para {$lookupId}");
                break;
        }

        if ($inboxRow && !empty($inboxRow['id'])) {
            Database::query(
                "UPDATE webhook_events SET processed_at = NOW(), process_result = ?, error_message = NULL WHERE id = ?",
                ['ok', (int) $inboxRow['id']]
            );
        }

        http_response_code(200);
        echo json_encode(['received' => true]);
    }

    /**
     * Endpoint legado do Asaas.
     *
     * O gateway foi trocado por Woovi, mas uma cobranca do Asaas gerada ANTES do
     * cutover pode ser paga DEPOIS. Sem esta rota, esse webhook levaria 404 e a
     * cliente pagaria sem receber acesso, sem deixar rastro.
     *
     * Nao processamos automaticamente (nao ha mais como validar na API do Asaas):
     * gravamos no inbox marcado para revisao manual e gritamos no log.
     */
    public function legacyAsaasWebhook(): void
    {
        $payload = (string) file_get_contents('php://input');

        $event = json_decode($payload, true);
        $eventType = is_array($event) ? (string) ($event['event'] ?? 'unknown') : 'unparseable';
        $paymentId = '';
        if (is_array($event) && isset($event['payment']) && is_array($event['payment'])) {
            $paymentId = (string) ($event['payment']['id'] ?? '');
        }

        error_log(
            "ATENCAO — webhook do Asaas (gateway descontinuado) recebido: evento={$eventType} " .
            "pagamento={$paymentId}. Se for pagamento confirmado, liberar acesso MANUALMENTE no /admin."
        );

        try {
            Database::query(
                "INSERT INTO webhook_events (provider, event_key, event_type, payment_id, payload_hash, payload_json, attempts, process_result)
                 VALUES ('asaas', ?, ?, ?, ?, ?, 1, 'needs_manual_review')
                 ON DUPLICATE KEY UPDATE attempts = attempts + 1, received_at = NOW()",
                [
                    substr(hash('sha256', $payload), 0, 80),
                    $eventType,
                    $paymentId,
                    hash('sha256', $payload),
                    $payload,
                ]
            );
        } catch (Throwable $e) {
            error_log('Webhook legado do Asaas: falha ao gravar inbox: ' . $e->getMessage());
        }

        // 200 para o Asaas parar de reenviar — o rastro ja esta no inbox e no log.
        http_response_code(200);
        echo json_encode(['received' => true, 'deprecated' => 'gateway migrado para woovi']);
    }

    /**
     * Mark an order as paid and grant product access, fire events and send
     * the confirmation email.
     */
    private function handlePaymentPaid(array $order, string $paymentMethod, string $eventType): void
    {
        // Idempotency — only the first processor flips pending->paid.
        $updated = Database::query(
            "UPDATE orders
             SET status = 'paid', paid_at = NOW(), payment_method = ?, provider_event = ?
             WHERE id = ? AND status <> 'paid'",
            [$paymentMethod, $eventType, $order['id']]
        )->rowCount();

        $shouldDispatch = $updated > 0;
        if (!$shouldDispatch) {
            // Still record the last provider event for visibility.
            Database::query("UPDATE orders SET provider_event = ? WHERE id = ?", [$eventType, $order['id']]);
        }

        $userId = $order['user_id'];
        $guestUserCreated = false;
        if (!$userId && !empty($order['customer_email'])) {
            // Bind to existing user OR create a new local account for guest purchases.
            $user = Database::fetch("SELECT id FROM users WHERE LOWER(email) = ?", [strtolower((string) $order['customer_email'])]);
            if ($user) {
                $userId = (int) $user['id'];
            } else {
                $name = trim((string) ($order['customer_name'] ?? ''));
                $created = Auth::ensureUserForGuestPurchase($name !== '' ? $name : 'Membro', (string) $order['customer_email']);
                $userId = (int) $created['id'];
                $guestUserCreated = !empty($created['created']);
            }
            if ($userId) {
                Database::query("UPDATE orders SET user_id = ? WHERE id = ?", [$userId, $order['id']]);
            }
        }

        if ($userId) {
            Database::query(
                "INSERT IGNORE INTO user_products (user_id, product_id) VALUES (?, ?)",
                [$userId, $order['product_id']]
            );

            // Guest checkout: send a "defina sua senha" email once (only if we created the user now).
            if ($shouldDispatch && $guestUserCreated) {
                $token = Auth::createResetTokenForUserId((int) $userId);
                if ($token) {
                    $resetUrl = APP_URL . '/reset-password?token=' . $token;
                    EventDispatcher::dispatch('user.password_reset_requested', [
                        'email' => (string) $order['customer_email'],
                        'attributes' => [
                            'name' => (string) ($order['customer_name'] ?? null),
                        ],
                        'properties' => [
                            'reset_url' => $resetUrl,
                            'source' => 'checkout_guest',
                        ],
                    ]);
                }
            }

            if ($shouldDispatch) {
                EventDispatcher::dispatch('product.access_granted', [
                    'email' => $order['customer_email'],
                    'properties' => [
                        'product_slug' => $order['product_slug'],
                        'product_id' => (int) $order['product_id'],
                        'product_name' => $order['product_title'],
                        'order_id' => (int) $order['id'],
                        'payment_method' => $paymentMethod,
                    ],
                ]);
            }
        }

        if ($shouldDispatch) {
            EventDispatcher::dispatch('order.paid', [
                'email' => $order['customer_email'],
                'properties' => [
                    'order_id' => (int) $order['id'],
                    'product_slug' => $order['product_slug'],
                    'amount' => (float) $order['amount'],
                    'payment_method' => $paymentMethod,
                ],
            ]);
        }
    }
}
