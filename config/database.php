<?php
/**
 * Database Configuration
 *
 * Reads credentials from /.env at runtime.
 */
+
$host = Env::require('DB_HOST');
$dbname = Env::require('DB_NAME');
$username = Env::require('DB_USER');
$password = (string) Env::get('DB_PASS', '');
$charset = (string) Env::get('DB_CHARSET', 'utf8mb4');
+
return [
    'host' => $host,
    'dbname' => $dbname,
    'username' => $username,
    'password' => $password,
    'charset' => $charset,
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
