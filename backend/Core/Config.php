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
    'api_key' => 'COLOQUE_SUA_CHAVE_AQUI', 
    'model' => 'openai/gpt-oss-120b:free',
];

const CONFIG = [
    'app_name' => APP_NAME,
    'db' => DB_CONFIG,
    'openrouter' => OPENROUTER_CONFIG,
];