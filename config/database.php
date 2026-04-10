<?php
/**
 * Database Configuration (Hostinger MySQL)
 *
 * Reads from .env — no hardcoded secrets.
 */

return [
    'host' => Env::require('DB_HOST'),
    'dbname' => Env::require('DB_NAME'),
    'username' => Env::require('DB_USER'),
    'password' => Env::require('DB_PASS'),
    'charset' => Env::get('DB_CHARSET', 'utf8mb4'),
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
