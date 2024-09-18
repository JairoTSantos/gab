<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class StatusPostagensModel {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }

    public function NovoStatusPostagem($dados) {
        try {
            $query = "INSERT INTO postagem_status (postagem_status_nome, postagem_status_descricao, postagem_status_criado_por) 
                      VALUES (:postagem_status_nome, :postagem_status_descricao, :postagem_status_criado_por)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':postagem_status_nome', $dados['postagem_status_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':postagem_status_descricao', $dados['postagem_status_descricao'], PDO::PARAM_STR);
            $stmt->bindParam(':postagem_status_criado_por', $dados['postagem_status_criado_por'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) { // Erro de duplicação
                return ['status' => 'duplicated'];
            }
            $this->logger->novoLog('postagem_status_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function AtualizarStatusPostagem($id, $dados) {
        try {
            $query = "UPDATE postagem_status 
                      SET postagem_status_nome = :postagem_status_nome, 
                          postagem_status_descricao = :postagem_status_descricao 
                      WHERE postagem_status_id = :postagem_status_id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':postagem_status_nome', $dados['postagem_status_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':postagem_status_descricao', $dados['postagem_status_descricao'], PDO::PARAM_STR);
            $stmt->bindParam(':postagem_status_id', $id, PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('postagem_status_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ApagarStatusPostagem($id) {
        try {
            $query = "DELETE FROM postagem_status WHERE postagem_status_id = :id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            // Verifica erro de chave estrangeira
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) { // Conflito de exclusão por chave estrangeira
                return ['status' => 'delete_conflict'];
            }
            $this->logger->novoLog('postagem_status_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ListarStatusPostagens() {
        try {
            $query = "SELECT * FROM view_postagens_status ORDER BY postagem_status_nome ASC";

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
            $this->logger->novoLog('postagem_status_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function BuscarStatusPostagem($coluna, $valor) {
        try {
            $query = "SELECT * FROM view_postagens_status WHERE $coluna = :valor";

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
            $this->logger->novoLog('postagem_status_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }
}
