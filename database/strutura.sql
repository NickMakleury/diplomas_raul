-- Criação do Banco de Dados
CREATE DATABASE IF NOT EXISTS diplomas_raul CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE diplomas_raul;

-- Tabela para salvar o histórico de conversas do Chatbot
CREATE TABLE IF NOT EXISTS historico_chat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mensagem_usuario TEXT NOT NULL,
    resposta_bot TEXT NOT NULL,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);