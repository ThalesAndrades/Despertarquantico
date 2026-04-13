<?php

return [
    'client_id' => Env::get('GOOGLE_OAUTH_CLIENT_ID', ''),
    'client_secret' => Env::get('GOOGLE_OAUTH_CLIENT_SECRET', ''),
    'redirect_uri' => Env::get('GOOGLE_OAUTH_REDIRECT_URI', APP_URL . '/auth/google/callback'),
    'issuer_allowed' => ['https://accounts.google.com', 'accounts.google.com'],
    'scopes' => 'openid email profile',
];

