<?php

header('Content-Type: application/json; charset=utf-8');

// 👇 A LINHA MÁGICA QUE FALTAVA ESTÁ AQUI 👇
require_once __DIR__ . '/Core/Config.php';

// A linha do autoload.php sumiu daqui!
require_once __DIR__ . '/chat_helpers.php';

$clinicProfile = require __DIR__ . '/clinic_profile.php';

// 1. Recebe os dados enviados pelo JavaScript.
$payload = json_decode(file_get_contents('php://input'), true) ?: [];

$mode = $payload['mode'] ?? 'manual';
$message = trim($payload['message'] ?? '');
$conversationId = isset($payload['conversation_id']) ? (int) $payload['conversation_id'] : 0;

$visitorName = trim($payload['visitor_name'] ?? '') ?: null;
$visitorPhone = trim($payload['visitor_phone'] ?? '') ?: null;
$visitorEmail = trim($payload['visitor_email'] ?? '') ?: null;

if ($message === '') {
    http_response_code(422);
    echo json_encode(['error' => 'Mensagem vazia.']);
    exit;
}

if ($conversationId <= 0) {
    $conversationId = createConversation($visitorName, $visitorPhone, $visitorEmail, $mode === 'ai' ? 'ai' : 'manual');
}

// 2. Salva a mensagem do visitante.
saveMessage($conversationId, 'user', $message);

// 3. Decide se a resposta vem do menu manual ou da IA.
if ($mode === 'ai') {
    $history = getConversationHistoryForAi($conversationId, 12);
    $responseText = getAiResponse($message, APP_NAME, OPENROUTER_CONFIG, $clinicProfile, $history);
    $sender = 'ai';
} else {
    $responseText = getManualResponse($message);
    $sender = 'bot';
}

// 4. Salva a resposta e devolve para o JavaScript.
saveMessage($conversationId, $sender, $responseText);

echo json_encode([
    'conversation_id' => $conversationId,
    'mode' => $mode,
    'reply_sender' => $sender,
    'reply' => $responseText,
]);

/**
 * Regras do chatbot manual.
 */
function getManualResponse($message)
{
    $clinicProfile = require __DIR__ . '/clinic_profile.php';
    $normalized = mb_strtolower(trim($message));

    // Tenta buscar na tabela chatbot_options
    $optionResponse = getManualOptionResponse($message);
    if ($optionResponse !== null) {
        return $optionResponse;
    }

    // Suporte a opções numéricas extras (manual didático)
    if (in_array($normalized, ['5', 'menu', 'opções', 'opcoes'], true)) {
        return buildManualMenu();
    }

    if (in_array($normalized, ['6', 'convenio', 'convênio', 'convenios', 'convênios'], true)) {
        $convenios = implode(', ', $clinicProfile['convenios'] ?? []);
        return "Convênios atendidos: {$convenios}.";
    }

    if (in_array($normalized, ['7', 'pagamento', 'pagamentos', 'formas de pagamento'], true)) {
        $pagamentos = implode(', ', $clinicProfile['formas_pagamento'] ?? []);
        return "Formas de pagamento: {$pagamentos}.";
    }

    if (in_array($normalized, ['8', 'preco', 'preços', 'precos', 'valor', 'valores'], true)) {
        $faixa = $clinicProfile['faixa_valores'] ?? [];
        return "Faixa de valores (estimativa):\n"
            . "- Avaliação inicial: " . ($faixa['avaliacao_inicial'] ?? 'sob consulta') . "\n"
            . "- Limpeza: " . ($faixa['limpeza'] ?? 'sob consulta') . "\n"
            . "- Clareamento: " . ($faixa['clareamento'] ?? 'sob consulta') . "\n"
            . "- Restauração: " . ($faixa['restauracao'] ?? 'sob consulta') . "\n"
            . ($faixa['observacao'] ?? '');
    }

    if (in_array($normalized, ['9', 'urgencia', 'urgência', 'emergencia', 'emergência'], true)) {
        $urgencia = $clinicProfile['politicas']['urgencia'] ?? 'Atendemos urgência conforme disponibilidade.';
        $whatsapp = $clinicProfile['whatsapp'] ?? '';
        return "{$urgencia}\nPara agilizar, chame no WhatsApp: {$whatsapp}.";
    }

    if (in_array($normalized, ['10', 'agendar', 'agendamento', 'consulta'], true)) {
        $agendamento = $clinicProfile['agendamento'] ?? [];
        return "Agendamento:\n"
            . "- Canais: " . ($agendamento['canais'] ?? 'WhatsApp e telefone') . "\n"
            . "- Dados necessários: " . ($agendamento['dados_necessarios'] ?? 'nome e telefone') . "\n"
            . "- Confirmação: " . ($agendamento['tempo_medio_confirmacao'] ?? 'em horário comercial');
    }

    // Palavras-chave de localização e horário
    if (containsAny($normalized, ['endereco', 'endereço', 'localizacao', 'localização', 'onde fica'])) {
        return "Endereço: " . ($clinicProfile['endereco'] ?? 'Não informado.')
            . "\nReferência: " . ($clinicProfile['ponto_referencia'] ?? 'Não informada.');
    }

    if (containsAny($normalized, ['horario', 'horário', 'funcionamento'])) {
        $h = $clinicProfile['horario'] ?? [];
        return "Horário de atendimento:\n"
            . "- Segunda a sexta: " . ($h['segunda_a_sexta'] ?? '-') . "\n"
            . "- Sábado: " . ($h['sabado'] ?? '-') . "\n"
            . "- Domingo: " . ($h['domingo'] ?? '-') . "\n"
            . "- Feriados: " . ($h['feriados'] ?? '-');
    }

    if (containsAny($normalized, ['servico', 'serviço', 'tratamento', 'tratamentos'])) {
        $servicos = $clinicProfile['servicos'] ?? [];
        return "Nossos serviços:\n- " . implode("\n- ", $servicos);
    }

    if (containsAny($normalized, ['telefone', 'contato', 'whatsapp', 'email', 'e-mail'])) {
        return "Contatos da clínica:\n"
            . "- Telefone: " . ($clinicProfile['telefone'] ?? '-') . "\n"
            . "- WhatsApp: " . ($clinicProfile['whatsapp'] ?? '-') . "\n"
            . "- E-mail: " . ($clinicProfile['email'] ?? '-');
    }

    return "Posso te ajudar com:\n" . buildManualMenu();
}

/**
 * Menu de respostas do chatbot manual.
 */
function buildManualMenu()
{
    return "1 - Conhecer serviços\n"
        . "2 - Horário de atendimento\n"
        . "3 - Localização\n"
        . "4 - Falar com atendente\n"
        . "5 - Ver este menu novamente\n"
        . "6 - Convênios\n"
        . "7 - Formas de pagamento\n"
        . "8 - Faixa de valores\n"
        . "9 - Urgência odontológica\n"
        . "10 - Como agendar consulta";
}

/**
 * Verifica se um texto contém alguma palavra-chave.
 */
function containsAny($haystack, $keywords)
{
    foreach ($keywords as $word) {
        if (str_contains($haystack, $word)) {
            return true;
        }
    }
    return false;
}

/**
 * Integração com OpenRouter usando Guzzle.
 */
/**
 * Integração com OpenRouter usando cURL nativo (Sem precisar do Guzzle).
 */
function getAiResponse(
    $userMessage,
    $companyName,
    $openRouter,
    $clinicProfile,
    $history = []
)
{
    if (empty($openRouter['api_key'])) {
        return 'Integração IA indisponível: configure OPENROUTER_CONFIG["api_key"] em backend/Core/Config.php.';
    }

    $clinicContext = buildClinicContext($clinicProfile);
    $systemPrompt = "Você é o assistente virtual do Raúl, um fotógrafo profissional especializado em fotos para diplomas, formaturas e documentos corporativos. "
        . "Seu objetivo é atender os clientes, tirar dúvidas sobre pacotes, preços, prazos, roupas ideais para a foto e agendamentos. "
        . "Responda em português do Brasil, com tom simpático, artístico e muito educado. "
        . "REGRA MÁXIMA E INQUEBRÁVEL: Você DEVE responder APENAS sobre assuntos relacionados ao serviço de fotografia do Raúl. "
        . "Se o usuário perguntar sobre receitas, política, matemática, programação, assuntos médicos, clínica odontológica ou QUALQUER outro assunto que não seja o serviço de fotografia do Raúl, VOCÊ DEVE RECUSAR educadamente dizendo: 'Desculpe, eu sou o assistente do Raúl e fui programado apenas para tirar dúvidas sobre nossos serviços de fotografia e agendamentos.' "
        . "Responda sempre em texto simples, sem Markdown, sem asteriscos, sem negrito e sem listas.\n\n"
        . "INFORMAÇÕES OFICIAIS DO ESTÚDIO FOTOGRÁFICO DO RAÚL:\n{$clinicContext}";

    $messages = buildMessagesForAi($systemPrompt, $history, $userMessage);

    // MÁGICA NATIVA DO PHP (Substitui o Guzzle)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://openrouter.ai/api/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Evita o erro do XAMPP

    $payload = [
        "model" => $openRouter['model'],
        "messages" => $messages,
        "temperature" => 0.7
    ];

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $openRouter['api_key'],
        "Content-Type: application/json",
        "HTTP-Referer: http://localhost",
        "X-Title: " . $companyName . " Chatbot"
    ]);

    $result = curl_exec($ch);
    $err = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($err) {
        return 'ERRO REAL DE CONEXÃO: ' . $err;
    }

    $data = json_decode($result, true);

    if ($httpCode !== 200) {
        $msgErro = $data['error']['message'] ?? "Erro desconhecido.";
        return "🚨 Erro na API (Código $httpCode): " . $msgErro;
    }

    $content = $data['choices'][0]['message']['content'] ?? null;

    if (!$content) {
        return 'Não consegui gerar resposta agora. Tente novamente em instantes.';
    }

    return sanitizeAiText($content);
}

/**
 * Converte os dados da clínica em texto para o prompt da IA.
 */
function buildClinicContext($profile)
{
    $lines = [];
    $lines[] = 'Nome: ' . ($profile['nome'] ?? '');
    $lines[] = 'Descrição: ' . ($profile['descricao'] ?? '');
    $lines[] = 'Endereço: ' . ($profile['endereco'] ?? '');
    $lines[] = 'Telefone: ' . ($profile['telefone'] ?? '');
    $lines[] = 'WhatsApp: ' . ($profile['whatsapp'] ?? '');
    $lines[] = 'E-mail: ' . ($profile['email'] ?? '');

    if (!empty($profile['horario']) && is_array($profile['horario'])) {
        $lines[] = 'Horários:';
        foreach ($profile['horario'] as $dia => $hora) {
            $lines[] = '- ' . $dia . ': ' . $hora;
        }
    }

    if (!empty($profile['servicos']) && is_array($profile['servicos'])) {
        $lines[] = 'Serviços: ' . implode(', ', $profile['servicos']);
    }

    if (!empty($profile['convenios']) && is_array($profile['convenios'])) {
        $lines[] = 'Convênios: ' . implode(', ', $profile['convenios']);
    }

    if (!empty($profile['formas_pagamento']) && is_array($profile['formas_pagamento'])) {
        $lines[] = 'Formas de pagamento: ' . implode(', ', $profile['formas_pagamento']);
    }

    if (!empty($profile['politicas']) && is_array($profile['politicas'])) {
        $lines[] = 'Políticas:';
        foreach ($profile['politicas'] as $chave => $texto) {
            $lines[] = '- ' . $chave . ': ' . $texto;
        }
    }

    if (!empty($profile['faq']) && is_array($profile['faq'])) {
        $lines[] = 'FAQ:';
        foreach ($profile['faq'] as $item) {
            $pergunta = $item['pergunta'] ?? '';
            $resposta = $item['resposta'] ?? '';
            $lines[] = '- P: ' . $pergunta;
            $lines[] = '  R: ' . $resposta;
        }
    }

    return implode("\n", $lines);
}

/**
 * Monta a lista de mensagens para a API da IA.
 */
function buildMessagesForAi($systemPrompt, $history, $currentMessage)
{
    $messages = [
        ['role' => 'system', 'content' => $systemPrompt],
    ];

    foreach ($history as $msg) {
        if (!isset($msg['role'], $msg['content'])) {
            continue;
        }
        $messages[] = [
            'role' => $msg['role'],
            'content' => $msg['content'],
        ];
    }

    $messages[] = ['role' => 'user', 'content' => $currentMessage];
    return $messages;
}

/**
 * Remove formatações comuns de Markdown para exibir texto puro
 * no estilo esperado para interfaces simples (ex.: chat estilo WhatsApp).
 */
function sanitizeAiText($text)
{
    // Remove blocos de código
    $text = preg_replace('/```[\s\S]*?```/u', '', $text) ?? $text;

    // Remove marcações de negrito/itálico/code inline
    $text = str_replace(['**', '__', '*', '_', '`'], '', $text);

    // Remove título markdown no início de linha
    $text = preg_replace('/^\s*#+\s*/mu', '', $text) ?? $text;

    // Remove bullets markdown no início de linha
    $text = preg_replace('/^\s*[-+]\s+/mu', '', $text) ?? $text;

    // Normaliza espaços e quebras excessivas
    $text = preg_replace("/\n{3,}/", "\n\n", $text) ?? $text;

    return trim($text);
}
