<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class ProfissaoModel {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }

    public function NovaProfissao($dados) {
        try {

            $query = "INSERT INTO pessoas_profissoes (pessoas_profissoes_nome, pessoas_profissoes_descricao, pessoas_profissoes_criado_por) VALUES (:pessoas_profissoes_nome, :pessoas_profissoes_descricao, :pessoas_profissoes_criado_por)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':pessoas_profissoes_nome', $dados['pessoas_profissoes_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoas_profissoes_descricao', $dados['pessoas_profissoes_descricao'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoas_profissoes_criado_por', $dados['pessoas_profissoes_criado_por'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            $this->logger->novoLog('profissoes_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function AtualizarProfissao($id, $dados) {
        try {
            $query = "UPDATE pessoas_profissoes SET pessoas_profissoes_nome = :pessoas_profissoes_nome, pessoas_profissoes_descricao = :pessoas_profissoes_descricao WHERE pessoas_profissoes_id = :pessoas_profissoes_id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':pessoas_profissoes_nome', $dados['pessoas_profissoes_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoas_profissoes_descricao', $dados['pessoas_profissoes_descricao'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoas_profissoes_criado_por', $dados['pessoas_profissoes_criado_por'], PDO::PARAM_INT);
            $stmt->bindParam(':pessoas_profissoes_id', $id, PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('profissoes_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ApagarProfissao($id) {

        $query = "DELETE FROM pessoas_profissoes WHERE pessoas_profissoes_id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict'];
            }
            $this->logger->novoLog('profissoes_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ListarProfissoes() {

      
        $query = "SELECT * FROM pessoas_profissoes ORDER BY pessoas_profissoes_nome ASC";

        try {
            $stmt = $this->db->prepare($query);
           
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                return ['status' => 'empty'];
            }

            return [
                'status' => 'success',
                'dados' => $result
            ];
        } catch (PDOException $e) {
            $this->logger->novoLog('profissoes_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
        }
    }

    public function BuscarProfissao($coluna, $valor) {

        $query = "SELECT * FROM pessoas_profissoes WHERE $coluna = :valor AND pessoas_profissoes_id <> 1000";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':valor', $valor);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (empty($result)) {
                return ['status' => 'empty'];
            }
            return [
                'status' => 'success',
                'dados' => $result
            ];
        } catch (PDOException $e) {
            $this->logger->novoLog('orgao_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
        }
    }
}
