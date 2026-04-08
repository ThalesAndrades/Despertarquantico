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
        $product = Database::fetch("SELECT * FROM products WHERE slug = ? AND is_active = 1", [$slug]);
        if (!$product) {
            flash('error', 'Produto não encontrado.');
            redirect('');
            return;
        }

        $email = '';
        if (isLoggedIn()) {
            $user = currentUser();
            $email = $user['email'];
        }

        // If no email provided, show email form
        $submittedEmail = trim($_POST['email'] ?? $_GET['email'] ?? '');
        if (empty($email) && empty($submittedEmail)) {
            view('checkout/email', compact('product'));
            return;
        }

        $email = $email ?: $submittedEmail;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'E-mail inválido.');
            redirect('checkout/' . $slug);
            return;
        }

        require_once BASE_PATH . '/src/Stripe.php';
        $stripe = new StripeClient();

        $amountCents = (int) ($product['price'] * 100);
        $successUrl = APP_URL . '/checkout/success?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = APP_URL . '/checkout/cancel';

        $session = $stripe->createCheckoutSession(
            $product['title'],
            $amountCents,
            $email,
            $successUrl,
            $cancelUrl,
            [
                'product_id' => $product['id'],
                'user_id' => $_SESSION['user_id'] ?? '',
            ]
        );

        if (!$session || empty($session['url'])) {
            flash('error', 'Erro ao processar pagamento. Tente novamente.');
            redirect('');
            return;
        }

        // Save pending order
        Database::insert(
            "INSERT INTO orders (user_id, product_id, stripe_session_id, customer_email, amount, currency, status)
             VALUES (?, ?, ?, ?, ?, 'brl', 'pending')",
            [
                $_SESSION['user_id'] ?? null,
                $product['id'],
                $session['id'],
                $email,
                $product['price'],
            ]
        );

        header('Location: ' . $session['url']);
        exit;
    }

    public function success(): void
    {
        $sessionId = $_GET['session_id'] ?? '';
        $order = null;
        $product = null;

        if ($sessionId) {
            $order = Database::fetch("SELECT o.*, p.title as product_title FROM orders o JOIN products p ON o.product_id = p.id WHERE o.stripe_session_id = ?", [$sessionId]);
        }

        view('checkout/success', compact('order'));
    }

    public function cancel(): void
    {
        view('checkout/cancel');
    }

    public function webhook(): void
    {
        // Do NOT check CSRF for webhooks
        $payload = file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        if (empty($payload) || empty($sigHeader)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing payload or signature']);
            return;
        }

        require_once BASE_PATH . '/src/Stripe.php';
        $stripe = new StripeClient();

        $event = $stripe->verifyWebhookSignature($payload, $sigHeader);
        if (!$event) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid signature']);
            return;
        }

        if (($event['type'] ?? '') === 'checkout.session.completed') {
            $session = $event['data']['object'] ?? [];
            $sessionId = $session['id'] ?? '';
            $paymentIntent = $session['payment_intent'] ?? '';
            $metadata = $session['metadata'] ?? [];
            $customerEmail = $session['customer_email'] ?? ($session['customer_details']['email'] ?? '');

            // Update order
            Database::query(
                "UPDATE orders SET status = 'paid', stripe_payment_intent = ?, paid_at = NOW() WHERE stripe_session_id = ?",
                [$paymentIntent, $sessionId]
            );

            $order = Database::fetch("SELECT * FROM orders WHERE stripe_session_id = ?", [$sessionId]);

            if ($order) {
                // Grant product access
                $userId = $order['user_id'];

                // If user doesn't exist yet, find by email
                if (!$userId && $customerEmail) {
                    $user = Database::fetch("SELECT id FROM users WHERE email = ?", [$customerEmail]);
                    if ($user) {
                        $userId = $user['id'];
                        Database::query("UPDATE orders SET user_id = ? WHERE id = ?", [$userId, $order['id']]);
                    }
                }

                if ($userId) {
                    // Grant access (ignore duplicate)
                    Database::query(
                        "INSERT IGNORE INTO user_products (user_id, product_id) VALUES (?, ?)",
                        [$userId, $order['product_id']]
                    );
                }
            }
        }

        http_response_code(200);
        echo json_encode(['received' => true]);
    }
}
