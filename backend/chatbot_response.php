<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/Core/Config.php';
require_once __DIR__ . '/chat_helpers.php';

// Carrega o perfil do estúdio de fotografia
$studioProfile = require __DIR__ . '/studio_profile.php';

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
    // Passamos o perfil do estúdio para a IA
    $responseText = getAiResponse($message, APP_NAME, OPENROUTER_CONFIG, $studioProfile, $history);
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
    $studioProfile = require __DIR__ . '/studio_profile.php';
    $normalized = mb_strtolower(trim($message));

    // 1. Tenta buscar no banco de dados primeiro (caso você cadastre algo lá no futuro)
    $optionResponse = getManualOptionResponse($normalized);
    if ($optionResponse !== null) {
        return $optionResponse;
    }

    // Opção 5 - Mostrar Menu
    if (in_array($normalized, ['5', 'menu', 'opções', 'opcoes', 'oi', 'olá', 'ola'], true)) {
        return buildManualMenu();
    }

    // Opção 1 - Serviços
    if (in_array($normalized, ['1']) || containsAny($normalized, ['servico', 'serviço', 'fotos', 'trabalhos'])) {
        $servicos = $studioProfile['servicos'] ?? [];
        return "Nossos serviços de fotografia:\n- " . implode("\n- ", $servicos);
    }

    // Opção 2 - Horário (Ajustado para ler exatamente os dados do seu studio_profile)
    if (in_array($normalized, ['2']) || containsAny($normalized, ['horario', 'horário', 'funcionamento'])) {
        $h = $studioProfile['horario'] ?? [];
        return "Horário de atendimento:\n"
            . "- Segunda a quinta: " . ($h['segunda_a_quinta'] ?? '-') . "\n"
            . "- Sexta: " . ($h['sexta'] ?? '-') . "\n"
            . "- Sábado: " . ($h['sabado'] ?? '-') . "\n"
            . "- Domingo: " . ($h['domingo'] ?? 'Fechado');
    }

    // Opção 3 - Endereço
    if (in_array($normalized, ['3']) || containsAny($normalized, ['endereco', 'endereço', 'localizacao', 'localização', 'onde fica'])) {
        return "Nosso estúdio fica em: " . ($studioProfile['endereco'] ?? 'Não informado.');
    }

    // Opção 4 - Falar com o Raúl
    if (in_array($normalized, ['4', 'raul', 'falar', 'humano'])) {
        $wpp = $studioProfile['whatsapp'] ?? '';
        return "Para falar diretamente com o Raúl, chame neste WhatsApp: {$wpp}";
    }

    // Opção 6 - Escolas Parceiras
    if (in_array($normalized, ['6', 'parcerias', 'escolas', 'faculdades'], true)) {
        $parceiros = implode(', ', $studioProfile['parcerias'] ?? ['Nenhuma informada']);
        return "Trabalhamos em parceria com: {$parceiros}.";
    }

    // Opção 7 - Formas de pagamento
    if (in_array($normalized, ['7', 'pagamento', 'pagamentos', 'formas de pagamento'], true)) {
        $pagamentos = implode(', ', $studioProfile['formas_pagamento'] ?? ['Consulte no atendimento']);
        return "Formas de pagamento aceitas: {$pagamentos}.";
    }

    // Opção 8 - Valores
    if (in_array($normalized, ['8', 'preco', 'preços', 'precos', 'valor', 'valores'], true)) {
        $faixa = $studioProfile['faixa_valores'] ?? [];
        return "Sobre nossos valores:\n" . ($faixa['orcamentos'] ?? 'Consulte diretamente com o Raúl.');
    }

    // Opção 10 - Agendar
    if (in_array($normalized, ['10', 'agendar', 'marcar'])) {
        return "Para agendar, basta escolher o serviço desejado e nos informar a data, ou chamar no WhatsApp!";
    }

    // Se a pessoa digitar qualquer outra coisa que não é um número válido, mostra o menu.
    return "Como posso ajudar? Escolha uma opção digitando o NÚMERO correspondente:\n\n" . buildManualMenu();
}

function buildManualMenu()
{
    return "1 - Conhecer serviços de fotografia\n"
        . "2 - Horário do estúdio\n"
        . "3 - Endereço do estúdio\n"
        . "4 - Falar com o Raúl\n"
        . "5 - Ver opções novamente\n"
        . "6 - Escolas parceiras\n"
        . "7 - Formas de pagamento\n"
        . "8 - Valores dos pacotes\n"
        . "10 - Como agendar meu ensaio";
}

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
 * Integração com OpenRouter usando cURL nativo.
 */
function getAiResponse($userMessage, $companyName, $openRouter, $studioProfile, $history = [])
{
    if (empty($openRouter['api_key'])) {
        return 'Integração IA indisponível: configure a chave no Config.php.';
    }

    $studioContext = buildStudioContext($studioProfile);
    
    $systemPrompt = "Você é o assistente virtual do Raúl, um fotógrafo profissional especializado em fotos para diplomas, formaturas e retratos corporativos. "
        . "Seu objetivo é vender pacotes, tirar dúvidas e agendar sessões como se fosse um humano conversando casualmente pelo WhatsApp. "
        . "Siga ESTAS REGRAS DE COMPORTAMENTO rigorosamente: "
        . "1. PRECISÃO ABSOLUTA (CRÍTICO): NUNCA invente preços, prazos, descontos ou serviços. Use APENAS as informações oficiais fornecidas. Se não souber, diga que vai verificar com o Raúl. "
        . "2. CONCISÃO EXTREMA: Responda no máximo com 2 a 3 frases curtas. Vá direto ao ponto. NUNCA mande 'textões' nem use listas ou formatações de texto. "
        . "3. HUMANIDADE NA MEDIDA: Seja natural e amigável. Use no máximo 1 emoji por mensagem. "
        . "4. VARIAÇÃO DE REPERTÓRIO: Evite repetir frases iguais. Soe fluido e humano. "
        . "5. TOQUE ARTÍSTICO: Use linguagem da fotografia (ex: 'valorizar seu perfil', 'luz bem feita', 'eternizar o momento'). "
        . "6. ENGAJAMENTO: Quando fizer sentido, finalize com uma pergunta curta para manter o fluxo. "
        . "7. FOCO: Redirecione assuntos fora de fotografia com educação. "
        . "8. CONVERSÃO: Conduza para o agendamento pedindo data ou tipo de foto. "
        . "9. INVESTIGAÇÃO: Se faltar informação, faça uma pergunta simples antes de responder. "
        . "10. CONTROLE EMOCIONAL: Mantenha sempre o tom profissional. "
        . "INFORMAÇÕES OFICIAIS DO ESTÚDIO FOTOGRÁFICO DO RAÚL:\n{$studioContext}";

    $messages = buildMessagesForAi($systemPrompt, $history, $userMessage);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://openrouter.ai/api/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

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
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($result, true);

    if ($httpCode !== 200) {
        if ($httpCode === 403) {
            $erroTexto = strtolower($result);
            if (str_contains($erroTexto, 'self-harm') || str_contains($erroTexto, 'suicide')) {
                return "Sinto muito que você esteja passando por um momento tão difícil. O CVV oferece apoio emocional gratuito 24h. Ligue 188 ou acesse cvv.org.br. Você não está sozinho. 💛";
            }
            return "Desculpe, a sua mensagem não pôde ser processada por filtros de segurança.";
        }
        return "🚨 Erro na API (Código $httpCode).";
    }

    $content = $data['choices'][0]['message']['content'] ?? null;
    return $content ? sanitizeAiText($content) : 'Tente novamente em instantes.';
}

function buildStudioContext($profile)
{
    $lines = [];
    $lines[] = 'Nome: ' . ($profile['nome'] ?? '');
    $lines[] = 'Descrição: ' . ($profile['descricao'] ?? '');
    $lines[] = 'Endereço: ' . ($profile['endereco'] ?? '');
    $lines[] = 'Telefone: ' . ($profile['telefone'] ?? '');
    $lines[] = 'WhatsApp: ' . ($profile['whatsapp'] ?? '');
    $lines[] = 'E-mail: ' . ($profile['email'] ?? '');

    if (!empty($profile['horario'])) {
        $lines[] = 'Horários: ' . json_encode($profile['horario']);
    }

    if (!empty($profile['servicos'])) {
        $lines[] = 'Serviços: ' . implode(', ', $profile['servicos']);
    }

    if (!empty($profile['formas_pagamento'])) {
        $lines[] = 'Pagamento: ' . implode(', ', $profile['formas_pagamento']);
    }

    return implode("\n", $lines);
}

function buildMessagesForAi($systemPrompt, $history, $currentMessage)
{
    $messages = [['role' => 'system', 'content' => $systemPrompt]];
    foreach ($history as $msg) {
        $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
    }
    $messages[] = ['role' => 'user', 'content' => $currentMessage];
    return $messages;
}

// 👇 ESTA É A PARTE QUE FOI ATUALIZADA 👇
function sanitizeAiText($text)
{
    $text = preg_replace('/```[\s\S]*?```/u', '', $text);
    $text = str_replace(['**', '__', '*', '_', '`'], '', $text);
    $text = preg_replace("/\n{3,}/", "\n\n", $text);
    
    // O FILTRO ANTI-ALUCINAÇÃO QUE LIMPA JSON VAZADO:
    $text = preg_replace('/"\}\s*$/u', '', trim($text));
    $text = str_replace('"}', '', $text);
    $text = preg_replace('/"$/u', '', trim($text));

    return trim($text);
}