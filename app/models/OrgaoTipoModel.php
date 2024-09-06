<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class OrgaoTipoModel {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }

    public function NovoOrgaoTipo($dados) {
        try {
            $query = "INSERT INTO orgaos_tipos (orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) 
                      VALUES (:orgao_tipo_nome, :orgao_tipo_descricao, :orgao_tipo_criado_por)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':orgao_tipo_nome', $dados['orgao_tipo_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_tipo_descricao', $dados['orgao_tipo_descricao'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_tipo_criado_por', $dados['orgao_tipo_criado_por'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            // Verifica erro de chave duplicada
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            $this->logger->novoLog('orgaos_tipos_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function AtualizarOrgaoTipo($id, $dados) {
        try {
            $query = "UPDATE orgaos_tipos 
                      SET orgao_tipo_nome = :orgao_tipo_nome, 
                          orgao_tipo_descricao = :orgao_tipo_descricao 
                      WHERE orgao_tipo_id = :orgao_tipo_id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':orgao_tipo_nome', $dados['orgao_tipo_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_tipo_descricao', $dados['orgao_tipo_descricao'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_tipo_id', $id, PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('orgaos_tipos_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ApagarOrgaoTipo($id) {
        try {
            $query = "DELETE FROM orgaos_tipos WHERE orgao_tipo_id = :id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            // Verifica erro de chave estrangeira
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict'];
            }
            $this->logger->novoLog('orgaos_tipos_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ListarOrgaosTipos() {
        try {
            $query = "SELECT * FROM orgaos_tipos ORDER BY orgao_tipo_nome ASC";

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
            $this->logger->novoLog('orgaos_tipos_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function BuscarOrgaoTipo($coluna, $valor) {
        try {
            $query = "SELECT * FROM orgaos_tipos WHERE $coluna = :valor";

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
            $this->logger->novoLog('orgaos_tipos_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }
}
