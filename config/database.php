<?php
/**
 * Database Configuration
 * Update these values for your Hostinger MySQL database
 */

return [
    'host' => 'localhost',
    'dbname' => 'sunyan_platform',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
