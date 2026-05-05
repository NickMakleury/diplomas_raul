<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../vendor/autoload.php';

use Models\ChatMessage;

// Recebe o id da conversa pela URL: ?conversation_id=1
$conversationId = isset($_GET['conversation_id']) ? (int) $_GET['conversation_id'] : 0;

if ($conversationId <= 0) {
    echo json_encode(['messages' => []]);
    exit;
}

// Busca todas as mensagens da conversa em ordem cronológica.
$messages = (new ChatMessage())->listByConversation($conversationId);

echo json_encode(['messages' => $messages]);
