<?php
/**
 * Woovi (OpenPix) Payment Gateway Configuration
 *
 * Docs: https://developers.woovi.com
 * Env vars obrigatorias: WOOVI_APP_ID
 * Env vars opcionais:    WOOVI_ENV, WOOVI_TIMEOUT, WOOVI_WEBHOOK_PUBLIC_KEY
 *
 * A chave publica abaixo e a MESMA para todos os clientes da Woovi e serve para
 * validar o header `x-webhook-signature`. Por ser publica, pode viver no repo.
 * Fica sobrescrivel por env caso a Woovi rotacione a chave — assim nao e preciso
 * fazer deploy de codigo para reagir a uma rotacao.
 */

// Sem `const` aqui de proposito: este arquivo e carregado com `require` (nao
// `require_once`) a cada instancia do cliente, e uma constante seria redeclarada.
$wooviDefaultWebhookPublicKey = 'LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0KTUlHZk1BMEdDU3FHU0liM0RRRUJBUVVBQTRHTkFEQ0JpUUtCZ1FDLytOdElranpldnZxRCtJM01NdjNiTFhEdApwdnhCalk0QnNSclNkY2EzcnRBd01jUllZdnhTbmQ3amFnVkxwY3RNaU94UU84aWVVQ0tMU1dIcHNNQWpPL3paCldNS2Jxb0c4TU5waS91M2ZwNnp6MG1jSENPU3FZc1BVVUcxOWJ1VzhiaXM1WloySVpnQk9iV1NwVHZKMGNuajYKSEtCQUE4MkpsbitsR3dTMU13SURBUUFCCi0tLS0tRU5EIFBVQkxJQyBLRVktLS0tLQo=';

return [
    'app_id' => Env::get('WOOVI_APP_ID', ''),
    'env' => Env::get('WOOVI_ENV', 'production'),
    'base_url' => 'https://api.woovi.com/api/v1',
    'webhook_public_key' => Env::get('WOOVI_WEBHOOK_PUBLIC_KEY', $wooviDefaultWebhookPublicKey),
    'timeout' => (int) Env::get('WOOVI_TIMEOUT', 20),
    'currency' => 'BRL',
];
