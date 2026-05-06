-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/05/2026 às 06:31
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `diplomas_raul`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `chatbot_options`
--

CREATE TABLE `chatbot_options` (
  `id` int(11) NOT NULL,
  `option_key` varchar(50) NOT NULL,
  `response_text` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `chat_conversations`
--

CREATE TABLE `chat_conversations` (
  `id` int(11) NOT NULL,
  `visitor_name` varchar(100) DEFAULT NULL,
  `visitor_phone` varchar(30) DEFAULT NULL,
  `visitor_email` varchar(100) DEFAULT NULL,
  `chatbot_type` varchar(20) DEFAULT 'manual',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `chat_conversations`
--

INSERT INTO `chat_conversations` (`id`, `visitor_name`, `visitor_phone`, `visitor_email`, `chatbot_type`, `updated_at`, `created_at`) VALUES
(1, 'Visitante', NULL, NULL, 'ai', '2026-05-06 09:02:27', '2026-05-06 00:45:50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender`, `message`, `created_at`) VALUES
(26, 1, 'ai', 'Sou assistente do Raúl, aqui para cuidar das suas fotos. Como posso ajudar a eternizar seu momento?', '2026-05-06 03:47:22'),
(27, 1, 'user', 'olha, eu to com vontade de me desviver n tem sentido na vida, o q faço?', '2026-05-06 03:47:51'),
(28, 1, 'ai', '🚨 Erro na API (Código 403): openai/gpt-oss-120b:free requires moderation on OpenInference. Your input was flagged for \"self-harm/intent\". No credits were charged.', '2026-05-06 03:47:51'),
(29, 1, 'user', 'olha, eu to com vontade de me desviver n tem sentido na vida, o q faço?', '2026-05-06 04:01:58'),
(30, 1, 'ai', '🚨 Erro na API (Código 403): openai/gpt-oss-120b:free requires moderation on OpenInference. Your input was flagged for \"self-harm/intent\". No credits were charged.', '2026-05-06 04:02:00'),
(31, 1, 'user', 'olha, eu to com vontade de me desviver n tem sentido na vida, o q faço?', '2026-05-06 04:02:26'),
(32, 1, 'ai', 'Sinto muito que você esteja passando por um momento tão difícil. Por favor, saiba que você não está sozinho. O CVV oferece apoio emocional gratuito e sigiloso 24 horas por dia. Ligue 188 ou acesse cvv.org.br. Tem sempre alguém disposto a te ouvir. 💛', '2026-05-06 04:02:27');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `chatbot_options`
--
ALTER TABLE `chatbot_options`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `chatbot_options`
--
ALTER TABLE `chatbot_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `chat_conversations`
--
ALTER TABLE `chat_conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
