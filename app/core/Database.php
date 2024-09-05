<?php

class Database {

    private static $instance = null;
    private $connection;

    private function __construct() {
        require_once dirname(__DIR__) . '/core/Logger.php';
        $logger = new Logger();

        $config = require dirname(__DIR__) . '/config/config.php';
        $dbConfig = $config['db'];

        try {
            $this->connection = new PDO(
                "mysql:host=" . $dbConfig['host'] . ";dbname=" . $dbConfig['database'],
                $dbConfig['username'],
                $dbConfig['password']
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $logger->novoLog('database_error', 'Erro na conexão: ' . $e->getMessage());
            throw new Exception('Não foi possível conectar ao banco de dados.');
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
