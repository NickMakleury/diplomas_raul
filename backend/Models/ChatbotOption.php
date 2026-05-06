<?php

namespace Models;

use Core\Model;

/**
 * Model da tabela chatbot_options.
 */
class ChatbotOption extends Model
{
    protected string $table = 'chatbot_options';

    public function findActiveResponse(string $option): ?string
    {
        $row = $this->findOne([
            'option_number' => $option,
            'is_active' => 1, // <--- A MÁGICA FOI AQUI: Mudamos de 'active' para 'is_active'
        ], [
            'response',
        ]);

        return $row['response'] ?? null;
    }
}