<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class TipoPessoaModel {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }

    public function NovoTipoPessoa($dados) {
        try {
            $query = "INSERT INTO pessoas_tipos (pessoa_tipo_nome, pessoa_tipo_descricao, pessoa_tipo_criado_por) 
                      VALUES (:pessoa_tipo_nome, :pessoa_tipo_descricao, :pessoa_tipo_criado_por)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':pessoa_tipo_nome', $dados['pessoa_tipo_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_tipo_descricao', $dados['pessoa_tipo_descricao'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_tipo_criado_por', $dados['pessoa_tipo_criado_por'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            $this->logger->novoLog('tipos_pessoas_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function AtualizarTipoPessoa($id, $dados) {
        try {
            $query = "UPDATE pessoas_tipos 
                      SET pessoa_tipo_nome = :pessoa_tipo_nome, 
                          pessoa_tipo_descricao = :pessoa_tipo_descricao 
                      WHERE pessoa_tipo_id = :pessoa_tipo_id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':pessoa_tipo_nome', $dados['pessoa_tipo_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_tipo_descricao', $dados['pessoa_tipo_descricao'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_tipo_id', $id, PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('tipos_pessoas_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ApagarTipoPessoa($id) {
        try {
            $query = "DELETE FROM pessoas_tipos WHERE pessoa_tipo_id = :id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            // Verifica erro de chave estrangeira
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict'];
            }
            $this->logger->novoLog('tipos_pessoas_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ListarTiposPessoas() {
        try {
            $query = "SELECT * FROM view_pessoas_tipos ORDER BY pessoa_tipo_nome ASC";

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
            $this->logger->novoLog('tipos_pessoas_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function BuscarTipoPessoa($coluna, $valor) {
        try {
            $query = "SELECT * FROM view_pessoas_tipos WHERE $coluna = :valor AND pessoa_tipo_id <> 1000";

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
            $this->logger->novoLog('tipos_pessoas_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }
}
