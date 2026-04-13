<?php
/**
 * Asaas Payment Gateway Configuration
 *
 * Docs: https://docs.asaas.com
 * Required env vars: ASAAS_API_KEY, ASAAS_ENV, ASAAS_WEBHOOK_TOKEN
 */

$asaasEnv = Env::get('ASAAS_ENV', 'sandbox');
$baseUrl = $asaasEnv === 'production'
    ? 'https://api.asaas.com/v3'
    : 'https://sandbox.asaas.com/api/v3';

return [
    'api_key' => Env::get('ASAAS_API_KEY', ''),
    'env' => $asaasEnv,
    'base_url' => $baseUrl,
    'webhook_token' => Env::get('ASAAS_WEBHOOK_TOKEN', ''),
    'wallet_id' => Env::get('ASAAS_WALLET_ID', ''),
    'currency' => 'BRL',
];
