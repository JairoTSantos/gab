<?php


class Database {

    private static $instance = null;
    private $connection;

    private function __construct() {
        require_once dirname(__DIR__) . '/core/Logger.php';

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
            novoLog('database_error', 'Erro na conexão: ' . $e->getMessage());
            session_destroy();
            echo '<script>window.location.href = "' . $config['app']['url'] . '/erro-sistema";</script>';
            die();
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
