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

// ── Links rápidos reutilizáveis ───────────────────────────
define('LINK_AGENDAR',   'http://localhost/devweb/diplomas_raul/agendar.php');
define('LINK_WHATSAPP',  'https://wa.me/+557587100691');

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

    // 1. Tenta buscar no banco de dados primeiro
    $optionResponse = getManualOptionResponse($normalized);
    if ($optionResponse !== null) {
        return $optionResponse;
    }

    // Opção 5 - Mostrar Menu
    if (in_array($normalized, ['5', 'menu', 'opciones', 'hola'], true)) {
        return buildManualMenu();
    }

    // Opção 1 - Serviços
    if (in_array($normalized, ['1']) || containsAny($normalized, ['servicio', 'servicios', 'fotos', 'trabajos'])) {
        $servicos = $studioProfile['servicos'] ?? [];
        return "Nuestros servicios de fotografía:\n- " . implode("\n- ", $servicos);
    }

    // Opção 2 - Horário
    if (in_array($normalized, ['2']) || containsAny($normalized, ['horario', 'funcionamiento'])) {
        $h = $studioProfile['horario'] ?? [];
        return "Horario de atención:\n"
            . "- Lunes a jueves: " . ($h['segunda_a_quinta'] ?? '-') . "\n"
            . "- Viernes: " . ($h['sexta'] ?? '-') . "\n"
            . "- Sábado: " . ($h['sabado'] ?? '-') . "\n"
            . "- Domingo: " . ($h['domingo'] ?? 'Cerrado');
    }

    // Opção 3 - Endereço
    if (in_array($normalized, ['3']) || containsAny($normalized, ['direccion', 'dirección', 'ubicacion', 'ubicación', 'donde queda'])) {
        return "Nuestro estudio está en: " . ($studioProfile['endereco'] ?? 'No informado.');
    }

    // Opção 4 - Falar com o Raúl / WhatsApp
    if (in_array($normalized, ['4', 'raul', 'hablar', 'humano', 'whatsapp', 'wpp', 'zap']) ||
        containsAny($normalized, ['whatsapp', 'zap', 'falar', 'contato', 'contact'])) {
        return "Fale diretamente com o Raúl pelo WhatsApp 📲\n[👉 Abrir WhatsApp](" . LINK_WHATSAPP . ")";
    }

    // Opção 6 - Escolas Parceiras
    if (in_array($normalized, ['6', 'alianzas', 'escuelas', 'facultades'], true)) {
        $parceiros = implode(', ', $studioProfile['parcerias'] ?? ['Ninguna informada']);
        return "Trabajamos en alianza con: {$parceiros}.";
    }

    // Opção 7 - Formas de pagamento
    if (in_array($normalized, ['7', 'pago', 'pagos', 'formas de pago'], true)) {
        $pagamentos = implode(', ', $studioProfile['formas_pagamento'] ?? ['Consulta en atención']);
        return "Formas de pago aceptadas: {$pagamentos}.";
    }

    // Opção 8 - Valores
    if (in_array($normalized, ['8', 'precio', 'precios', 'valor', 'valores'], true)) {
        $faixa = $studioProfile['faixa_valores'] ?? [];
        return "Sobre nuestros valores:\n" . ($faixa['orcamentos'] ?? 'Consulta directamente con Raúl.');
    }

    // Opção 10 - Agendar sessão
    if (in_array($normalized, ['10', 'agendar', 'reservar', 'marcar']) ||
        containsAny($normalized, ['agendar', 'reservar', 'marcar', 'sess', 'fotografia', 'foto'])) {
        return "Ótimo! Você pode agendar sua sessão de duas formas:\n\n"
             . "📋 [Formulário online — clique aqui](" . LINK_AGENDAR . ")\n"
             . "📲 [WhatsApp direto com Raúl](" . LINK_WHATSAPP . ")\n\n"
             . "Pelo formulário você já deixa todos os dados e o Raúl confirma em até 24h! 🎓";
    }

    // Se a pessoa digitar qualquer outra coisa
    return "¿Cómo puedo ayudarte? Elige una opción escribiendo el NÚMERO correspondiente:\n\n" . buildManualMenu();
}

function buildManualMenu()
{
    return "1 - Conocer servicios de fotografía\n"
        . "2 - Horario del estudio\n"
        . "3 - Dirección del estudio\n"
        . "4 - Hablar por WhatsApp con Raúl\n"
        . "5 - Ver opciones nuevamente\n"
        . "6 - Escuelas en alianza\n"
        . "7 - Formas de pago\n"
        . "8 - Valores de los paquetes\n"
        . "10 - Agendar / Reservar mi sesión";
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

    $systemPrompt = "Eres el asistente virtual de Raúl, un fotógrafo profesional especializado en fotos para diplomas, graduaciones y retratos corporativos. "
        . "Tu objetivo es vender paquetes, resolver dudas y agendar sesiones como si fueras un humano conversando casualmente por WhatsApp. "
        . "Sigue ESTAS REGLAS DE COMPORTAMIENTO estrictamente: "
        . "1. IDIOMA OBLIGATORIO (CRÍTICO): Debes responder a TODAS las preguntas exclusivamente en Español, sin importar en qué idioma te hable el cliente. "
        . "2. PRECISIÓN ABSOLUTA (CRÍTICO): NUNCA inventes precios, plazos, descuentos o servicios. Usa SOLO la información oficial proporcionada. Si no lo sabes, di que lo verificarás con Raúl. "
        . "3. CONCISIÓN EXTREMA: Responde como máximo con 2 o 3 frases cortas. Ve al grano. NUNCA mandes 'textos largos' ni uses listas o formatos. "
        . "4. HUMANIDAD A MEDIDA: Sé natural y amigable. Usa como máximo 1 emoji por mensaje. "
        . "5. VARIACIÓN DE REPERTORIO: Evita repetir frases iguales. Suena fluido y humano. "
        . "6. TOQUE ARTÍSTICO: Usa lenguaje de fotografía (ej: 'resaltar tu perfil', 'buena iluminación', 'eternizar el momento'). "
        . "7. COMPROMISO: Cuando tenga sentido, termina con una pregunta corta para mantener el flujo de la conversación. "
        . "8. ENFOQUE: Redirige con educación los temas que no sean sobre fotografía. "
        . "9. CONVERSIÓN (CRÍTICO): Cuando el cliente quiera agendar, reservar una sesión, o pregunte cómo contactar a Raúl, DEBES responder EXACTAMENTE con este texto (sin modificar los links):\n"
        . "'¡Perfecto! Puedes agendar de dos formas:\n📋 [Formulario online](" . LINK_AGENDAR . ")\n📲 [WhatsApp con Raúl](" . LINK_WHATSAPP . ")\n¿Tienes fecha estimada de tu graduación?'\n"
        . "10. CONTROL EMOCIONAL: Mantén siempre el tono profesional. "
        . "INFORMACIÓN OFICIAL DEL ESTUDIO FOTOGRÁFICO DE RAÚL:\n{$studioContext}";

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
    $text = str_replace(['**', '__', '*', '`'], '', $text);
    $text = preg_replace("/\n{3,}/", "\n\n", $text);

    // O FILTRO ANTI-ALUCINAÇÃO QUE LIMPA JSON VAZADO:
    $text = preg_replace('/"\}\s*$/u', '', trim($text));
    $text = str_replace('"}', '', $text);
    $text = preg_replace('/"$/u', '', trim($text));

    return trim($text);
}
