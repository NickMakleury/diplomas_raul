-- Tabela de Agendamentos - Diplomas Raúl
-- Execute este script no phpMyAdmin ou via terminal MySQL

CREATE TABLE IF NOT EXISTS `agendamentos` (
  `id`              INT(11)       NOT NULL AUTO_INCREMENT,
  `nome`            VARCHAR(150)  NOT NULL,
  `email`           VARCHAR(150)  NOT NULL,
  `telefone`        VARCHAR(30)   DEFAULT NULL,
  `data_formatura`  DATE          NOT NULL,
  `curso`           VARCHAR(200)  NOT NULL,
  `mensagem`        TEXT          DEFAULT NULL,
  `status`          ENUM('pendente','confirmado','cancelado') NOT NULL DEFAULT 'pendente',
  `created_at`      TIMESTAMP     NOT NULL DEFAULT current_timestamp(),
  `updated_at`      TIMESTAMP     NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
