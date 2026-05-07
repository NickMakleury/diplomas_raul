<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// 🔥 resposta fake só pra testar conexão
echo json_encode([
    "status" => "ok",
    "msg" => "PHP está funcionando"
]);