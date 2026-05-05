<?php

// --- O NOSSO PORTEIRO AUTOMÁTICO (Substitui o autoload do Composer) ---
spl_autoload_register(function ($class) {
    // Transforma "Models\ChatConversation" em "Models/ChatConversation.php"
    $caminho = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($caminho)) {
        require_once $caminho;
    }
});
// --------------------------------------------------------------------

use Models\ChatConversation;
use Models\ChatMessage;
use Models\ChatbotOption;

/**
 * Cria uma nova conversa e retorna o id.
 */
function createConversation(?string $name, ?string $phone, ?string $email, string $chatbotType): int
{
    return (new ChatConversation())->create($name, $phone, $email, $chatbotType);
}

/**
 * Salva uma mensagem no histórico.
 */
function saveMessage(int $conversationId, string $sender, string $message): void
{
    (new ChatMessage())->create($conversationId, $sender, $message);

    // Marca a conversa como atualizada.
    (new ChatConversation())->updateActivity($conversationId);
}

/**
 * Busca mensagem pronta na tabela chatbot_options.
 */
function getManualOptionResponse(string $option): ?string
{
    return (new ChatbotOption())->findActiveResponse($option);
}

/**
 * Retorna parte do histórico da conversa para dar contexto à IA.
 */
function getConversationHistoryForAi(int $conversationId, int $limit = 12): array
{
    $rows = (new ChatMessage())->historyForAi($conversationId, $limit);
    if (!$rows) {
        return [];
    }

    // Estavam em ordem DESC; voltamos para ordem cronológica
    $rows = array_reverse($rows);

    $messages = [];
    foreach ($rows as $row) {
        $sender = $row['sender'] ?? 'user';
        $role = $sender === 'user' ? 'user' : 'assistant';
        $messages[] = [
            'role' => $role,
            'content' => $row['message'] ?? '',
        ];
    }

    return $messages;
}