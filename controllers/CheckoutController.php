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

        require_once BASE_PATH . '/src/Asaas.php';
        $asaas = new AsaasClient();

        // Reuse or create Asaas customer.
        $asaasCustomerId = null;
        if ($userId) {
            $existingUser = Database::fetch(
                "SELECT asaas_customer_id FROM users WHERE id = ?",
                [$userId]
            );
            if ($existingUser && !empty($existingUser['asaas_customer_id'])) {
                $asaasCustomerId = $existingUser['asaas_customer_id'];
            }
        }

        if (!$asaasCustomerId) {
            $customer = $asaas->createCustomer($name, $email, $submittedCpf ?: null);
            if (!$customer || empty($customer['id'])) {
                flash('error', 'Erro ao criar cadastro de pagamento. Tente novamente.');
                redirect('checkout/' . $slug);
                return;
            }
            $asaasCustomerId = $customer['id'];
            if ($userId) {
                Database::query(
                    "UPDATE users SET asaas_customer_id = ? WHERE id = ? AND (asaas_customer_id IS NULL OR asaas_customer_id = '')",
                    [$asaasCustomerId, $userId]
                );
            }
        }

        // Create order row first so we have a local reference to send to Asaas.
        // We use a temporary placeholder id; then update with the real Asaas id.
        $placeholder = 'pending_' . bin2hex(random_bytes(12));
        $orderId = Database::insert(
            "INSERT INTO orders (user_id, product_id, asaas_payment_id, customer_email, amount, currency, status, payment_method)
             VALUES (?, ?, ?, ?, ?, 'brl', 'pending', 'undefined')",
            [
                $userId,
                $product['id'],
                $placeholder,
                $email,
                $product['price'],
            ]
        );

        $successUrl = APP_URL . '/checkout/success?order=' . $orderId;

        $payment = $asaas->createPayment([
            'customer' => $asaasCustomerId,
            'billingType' => 'UNDEFINED',
            'value' => (float) $product['price'],
            'dueDate' => date('Y-m-d', strtotime('+3 days')),
            'description' => $product['title'] . ' - Despertar Espiral',
            'externalReference' => (string) $orderId,
            'callback' => [
                'successUrl' => $successUrl,
                'autoRedirect' => true,
            ],
        ]);

        if (!$payment || empty($payment['id']) || empty($payment['invoiceUrl'])) {
            // Roll back the pending order so we don't leave orphans.
            Database::query("DELETE FROM orders WHERE id = ?", [$orderId]);
            flash('error', 'Erro ao processar pagamento. Tente novamente.');
            redirect('checkout/' . $slug);
            return;
        }

        Database::query(
            "UPDATE orders SET asaas_payment_id = ?, asaas_invoice_url = ? WHERE id = ?",
            [$payment['id'], $payment['invoiceUrl'], $orderId]
        );

        EventDispatcher::dispatch('checkout.started', [
            'email' => $email,
            'attributes' => ['name' => $name],
            'properties' => [
                'product_slug' => $product['slug'],
                'product_title' => $product['title'],
                'amount' => (float) $product['price'],
                'order_id' => (int) $orderId,
            ],
        ]);

        header('Location: ' . $payment['invoiceUrl']);
        exit;
    }

    public function success(): void
    {
        $orderId = (int) ($_GET['order'] ?? 0);
        $order = null;
        if ($orderId > 0) {
            $order = Database::fetch(
                "SELECT o.*, p.title as product_title
                 FROM orders o JOIN products p ON o.product_id = p.id
                 WHERE o.id = ?",
                [$orderId]
            );
        }

        view('checkout/success', compact('order'));
    }

    public function cancel(): void
    {
        view('checkout/cancel');
    }

    /**
     * Asaas webhook endpoint — public, stateless, no session/CSRF.
     * Authenticated via the `asaas-access-token` header (pre-shared token
     * matching ASAAS_WEBHOOK_TOKEN in .env).
     */
    public function webhook(): void
    {
        $payload = file_get_contents('php://input');
        $headerToken = $_SERVER['HTTP_ASAAS_ACCESS_TOKEN'] ?? '';

        if (empty($payload)) {
            http_response_code(400);
            echo json_encode(['error' => 'Empty payload']);
            return;
        }

        require_once BASE_PATH . '/src/Asaas.php';
        $asaas = new AsaasClient();

        if (!$asaas->verifyWebhookToken($headerToken)) {
            error_log('Asaas webhook rejected: invalid asaas-access-token header');
            http_response_code(401);
            echo json_encode(['error' => 'Invalid token']);
            return;
        }

        $event = json_decode($payload, true);
        if (!is_array($event) || empty($event['event'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Malformed payload']);
            return;
        }

        $eventType = $event['event'];
        $payment = $event['payment'] ?? [];
        $asaasPaymentId = $payment['id'] ?? '';
        $externalReference = $payment['externalReference'] ?? '';
        $billingType = strtolower($payment['billingType'] ?? 'undefined');
        $paymentMethod = $this->mapBillingTypeToMethod($billingType);

        if ($asaasPaymentId === '') {
            http_response_code(200);
            echo json_encode(['received' => true, 'ignored' => 'no payment id']);
            return;
        }

        $order = Database::fetch(
            "SELECT o.*, p.title as product_title, p.slug as product_slug
             FROM orders o JOIN products p ON o.product_id = p.id
             WHERE o.asaas_payment_id = ?",
            [$asaasPaymentId]
        );

        // Fallback: lookup by externalReference (order id) if we missed the race.
        if (!$order && $externalReference !== '' && ctype_digit((string) $externalReference)) {
            $order = Database::fetch(
                "SELECT o.*, p.title as product_title, p.slug as product_slug
                 FROM orders o JOIN products p ON o.product_id = p.id
                 WHERE o.id = ?",
                [(int) $externalReference]
            );
        }

        if (!$order) {
            error_log("Asaas webhook: order not found for payment {$asaasPaymentId} (event {$eventType})");
            http_response_code(200);
            echo json_encode(['received' => true, 'ignored' => 'order not found']);
            return;
        }

        switch ($eventType) {
            case 'PAYMENT_CONFIRMED':
            case 'PAYMENT_RECEIVED':
                $this->handlePaymentPaid($order, $paymentMethod, $eventType);
                break;

            case 'PAYMENT_OVERDUE':
                Database::query(
                    "UPDATE orders SET status = 'failed', asaas_event = ? WHERE id = ?",
                    [$eventType, $order['id']]
                );
                EventDispatcher::dispatch('order.overdue', [
                    'email' => $order['customer_email'],
                    'properties' => [
                        'order_id' => (int) $order['id'],
                        'product_slug' => $order['product_slug'],
                    ],
                ]);
                break;

            case 'PAYMENT_REFUNDED':
            case 'PAYMENT_DELETED':
                Database::query(
                    "UPDATE orders SET status = 'refunded', asaas_event = ? WHERE id = ?",
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
                    ],
                ]);
                break;

            default:
                // Log unhandled events for visibility but don't fail.
                error_log("Asaas webhook: unhandled event {$eventType} for payment {$asaasPaymentId}");
                break;
        }

        http_response_code(200);
        echo json_encode(['received' => true]);
    }

    /**
     * Mark an order as paid and grant product access, fire events and send
     * the confirmation email.
     */
    private function handlePaymentPaid(array $order, string $paymentMethod, string $eventType): void
    {
        // Idempotency — don't re-process PAYMENT_RECEIVED after PAYMENT_CONFIRMED.
        if ($order['status'] === 'paid') {
            Database::query(
                "UPDATE orders SET asaas_event = ? WHERE id = ?",
                [$eventType, $order['id']]
            );
            return;
        }

        Database::query(
            "UPDATE orders SET status = 'paid', paid_at = NOW(), payment_method = ?, asaas_event = ? WHERE id = ?",
            [$paymentMethod, $eventType, $order['id']]
        );

        $userId = $order['user_id'];
        if (!$userId && !empty($order['customer_email'])) {
            $user = Database::fetch("SELECT id FROM users WHERE email = ?", [$order['customer_email']]);
            if ($user) {
                $userId = $user['id'];
                Database::query("UPDATE orders SET user_id = ? WHERE id = ?", [$userId, $order['id']]);
            }
        }

        if ($userId) {
            Database::query(
                "INSERT IGNORE INTO user_products (user_id, product_id) VALUES (?, ?)",
                [$userId, $order['product_id']]
            );

            EventDispatcher::dispatch('product.access_granted', [
                'email' => $order['customer_email'],
                'properties' => [
                    'product_slug' => $order['product_slug'],
                    'product_id' => (int) $order['product_id'],
                ],
            ]);
        }

        EventDispatcher::dispatch('order.paid', [
            'email' => $order['customer_email'],
            'properties' => [
                'order_id' => (int) $order['id'],
                'product_slug' => $order['product_slug'],
                'amount' => (float) $order['amount'],
                'payment_method' => $paymentMethod,
            ],
        ]);

        require_once BASE_PATH . '/src/Mailer.php';
        Mailer::sendOrderConfirmation(
            $order['customer_email'],
            (int) $order['id'],
            $order['product_title'],
            $paymentMethod
        );
    }

    private function mapBillingTypeToMethod(string $billingType): string
    {
        return match ($billingType) {
            'pix' => 'pix',
            'credit_card' => 'credit_card',
            'boleto' => 'boleto',
            default => 'undefined',
        };
    }
}
