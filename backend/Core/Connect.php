<?php

namespace Core;

/**
 * Classe responsável pela conexão com o banco. [Singleton Pattern]
 *
 * Singleton significa: uma única conexão é criada e reutilizada.
 */
class Connect
{
    /**
     * Opções padrão do PDO.
     */
    private const OPTIONS = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_CASE => \PDO::CASE_NATURAL,
    ];

    /** @var \PDO|null */
    private static $instance = null;

    /**
     * Cria a conexão apenas uma vez e reutiliza nas próximas chamadas.
     */
    public static function getInstance(): \PDO
    {
        if (self::$instance) {
            return self::$instance;
        }

        try {
            $db = (object) DB_CONFIG;
            $dsn = "mysql:host={$db->host};port={$db->port};dbname={$db->name};charset=utf8mb4";

            self::$instance = new \PDO($dsn, $db->user, $db->pass, self::OPTIONS);
        } catch (\PDOException $exception) {
            http_response_code(500);
            exit('Erro ao conectar com o banco de dados.');
        }

        return self::$instance;
    }
}
