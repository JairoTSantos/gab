<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class TipoClippingModel {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }

    public function NovoClippingTipo($dados) {
        try {
            $query = "INSERT INTO clipping_tipos (clipping_tipo_nome, clipping_tipo_descricao, clipping_tipo_criado_por) 
                      VALUES (:clipping_tipo_nome, :clipping_tipo_descricao, :clipping_tipo_criado_por)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':clipping_tipo_nome', $dados['clipping_tipo_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':clipping_tipo_descricao', $dados['clipping_tipo_descricao'], PDO::PARAM_STR);
            $stmt->bindParam(':clipping_tipo_criado_por', $dados['clipping_tipo_criado_por'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            $this->logger->novoLog('clipping_tipos_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function AtualizarClippingTipo($id, $dados) {
        try {
            $query = "UPDATE clipping_tipos 
                      SET clipping_tipo_nome = :clipping_tipo_nome, 
                          clipping_tipo_descricao = :clipping_tipo_descricao 
                      WHERE clipping_tipo_id = :clipping_tipo_id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':clipping_tipo_nome', $dados['clipping_tipo_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':clipping_tipo_descricao', $dados['clipping_tipo_descricao'], PDO::PARAM_STR);
            $stmt->bindParam(':clipping_tipo_id', $id, PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('clipping_tipos_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ApagarClippingTipo($id) {
        try {
            $query = "DELETE FROM clipping_tipos WHERE clipping_tipo_id = :id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict'];
            }
            $this->logger->novoLog('clipping_tipos_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ListarClippingTipos() {
        try {
            $query = "SELECT * FROM view_tipo_clipping ORDER BY clipping_tipo_nome ASC";

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
            $this->logger->novoLog('clipping_tipos_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function BuscarClippingTipo($coluna, $valor) {
        try {
            $query = "SELECT * FROM view_tipo_clipping WHERE $coluna = :valor AND clipping_tipo_id <> 1000";

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
            $this->logger->novoLog('clipping_tipos_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }
}
