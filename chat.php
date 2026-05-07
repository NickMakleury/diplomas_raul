<?php

$apiKey = 'COLE_SUA_CHAVE_AQUI';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://openrouter.ai/api/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

$headers = [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json",
];

$data = [
    "model" => "openai/gpt-4o-mini",
    "messages" => [
        ["role" => "user", "content" => "Oi, responde aí"]
    ]
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

if(curl_errno($ch)) {
    echo 'Erro CURL: ' . curl_error($ch);
} else {
    echo $response;
}

curl_close($ch);