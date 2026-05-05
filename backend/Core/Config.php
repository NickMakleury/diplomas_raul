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

const OPENROUTER_CONFIG = [
    'api_key' => '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',
    'model' => 'openai/gpt-oss-120b:free',
];

const CONFIG = [
    'app_name' => APP_NAME,
    'db' => DB_CONFIG,
    'openrouter' => OPENROUTER_CONFIG,
];