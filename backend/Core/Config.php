<?php
// backend/Core/Config.php

// Sobe dois níveis para encontrar o .env na raiz do projeto
$envPath = __DIR__ . '/../../.env';

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignora comentários
        if (strpos(trim($line), '#') === 0) continue;

        // Verifica se a linha tem o formato CHAVE=VALOR
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            putenv(sprintf('%s=%s', trim($name), trim($value)));
        }
    }
}

const APP_NAME = 'Diplomas Raúl';

const DB_CONFIG = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'name' => 'diplomas_raul',
    'user' => 'root',
    'pass' => '',
];

// backend/Core/Config.php

define('OPENROUTER_CONFIG', [
    'api_key' => getenv('OPENROUTER_API_KEY') ?: 'chave-nao-configurada',
    'model'   => 'openai/gpt-oss-120b:free', 
]);

define('CONFIG', [
    'app_name' => APP_NAME,
    'db' => DB_CONFIG,
    'openrouter' => OPENROUTER_CONFIG,
]);
