<?php

class HomeController
{
    public function index(): void
    {
        // Fetch the main product for the CTA button
        $product = Database::fetch(
            "SELECT * FROM products WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 1"
        );
        view('landing/index', compact('product'));
    }
}
