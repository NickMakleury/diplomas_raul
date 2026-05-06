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