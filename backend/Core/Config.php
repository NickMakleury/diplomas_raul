<?php
// backend/Core/Config.php
const APP_NAME = 'Diplomas Raúl';

const DB_CONFIG = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'name' => 'diplomas_raul', // <-- MUDAMOS AQUI
    'user' => 'root',
    'pass' => '',
];

// backend/Core/Config.php
const OPENROUTER_CONFIG = [
    'api_key' => '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',
    'model'   => 'openai/gpt-oss-120b:free', 
];

const CONFIG = [
    'app_name' => APP_NAME,
    'db' => DB_CONFIG,
    'openrouter' => OPENROUTER_CONFIG,
];