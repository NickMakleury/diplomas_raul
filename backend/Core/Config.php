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