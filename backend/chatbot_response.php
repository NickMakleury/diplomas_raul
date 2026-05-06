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

    $optionResponse = getManualOptionResponse($message);
    if ($optionResponse !== null) {
        return $optionResponse;
    }

    if (in_array($normalized, ['5', 'menu', 'opções', 'opcoes'], true)) {
        return buildManualMenu();
    }

    // Ajustado de Convênios para algo mais fotográfico ou removido se não existir
    if (in_array($normalized, ['6', 'parcerias', 'escolas', 'faculdades'], true)) {
        $parceiros = implode(', ', $studioProfile['parcerias'] ?? ['Nenhuma informada']);
        return "Trabalhamos em parceria com: {$parceiros}.";
    }

    if (in_array($normalized, ['7', 'pagamento', 'pagamentos', 'formas de pagamento'], true)) {
        $pagamentos = implode(', ', $studioProfile['formas_pagamento'] ?? []);
        return "Formas de pagamento aceitas: {$pagamentos}.";
    }

    if (in_array($normalized, ['8', 'preco', 'preços', 'precos', 'valor', 'valores'], true)) {
        $faixa = $studioProfile['faixa_valores'] ?? [];
        return "Investimento médio:\n"
            . "- Foto para diploma: " . ($faixa['foto_diploma'] ?? 'sob consulta') . "\n"
            . "- Ensaio estúdio: " . ($faixa['ensaio_estudio'] ?? 'sob consulta') . "\n"
            . "- Cobertura formatura: " . ($faixa['formatura'] ?? 'sob consulta') . "\n"
            . ($faixa['observacao'] ?? '');
    }

    if (containsAny($normalized, ['endereco', 'endereço', 'localizacao', 'localização', 'onde fica'])) {
        return "Nosso estúdio fica em: " . ($studioProfile['endereco'] ?? 'Não informado.')
            . "\nReferência: " . ($studioProfile['ponto_referencia'] ?? 'Próximo ao centro.');
    }

    if (containsAny($normalized, ['horario', 'horário', 'funcionamento'])) {
        $h = $studioProfile['horario'] ?? [];
        return "Horário de atendimento:\n"
            . "- Segunda a sexta: " . ($h['segunda_a_sexta'] ?? '-') . "\n"
            . "- Sábado: " . ($h['sabado'] ?? '-') . "\n"
            . "- Domingo: " . ($h['domingo'] ?? 'Fechado');
    }

    if (containsAny($normalized, ['servico', 'serviço', 'fotos', 'trabalhos'])) {
        $servicos = $studioProfile['servicos'] ?? [];
        return "Nossos serviços de fotografia:\n- " . implode("\n- ", $servicos);
    }

    return "Como posso ajudar? Escolha uma opção:\n" . buildManualMenu();
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

    // Geramos o contexto baseado no perfil do estúdio
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

/**
 * Converte os dados do estúdio em texto para a IA.
 */
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

function sanitizeAiText($text)
{
    $text = preg_replace('/```[\s\S]*?```/u', '', $text);
    $text = str_replace(['**', '__', '*', '_', '`'], '', $text);
    $text = preg_replace("/\n{3,}/", "\n\n", $text);
    return trim($text);
}