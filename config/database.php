<?php
/**
 * Database Configuration - Hostinger Production
 */

return [
    'host' => 'localhost',
    'dbname' => 'u525832347_Mulherespiral',
    'username' => 'u525832347_Mulherespiral',
    'password' => '@Telemed123',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
