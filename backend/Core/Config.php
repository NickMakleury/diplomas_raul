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