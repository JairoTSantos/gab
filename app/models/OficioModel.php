<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class OficioModel {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }

    public function NovoOficio($dados) {
        try {
            $query = "INSERT INTO oficios (oficio_titulo, oficio_resumo, oficio_ano, oficio_arquivo, oficio_orgao, oficio_criado_por) VALUES (:oficio_titulo, :oficio_resumo, :oficio_ano, :oficio_arquivo,  :oficio_orgao, :oficio_criado_por)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':oficio_titulo', $dados['oficio_titulo'], PDO::PARAM_STR);
            $stmt->bindParam(':oficio_resumo', $dados['oficio_resumo'], PDO::PARAM_STR);
            $stmt->bindParam(':oficio_ano', $dados['oficio_ano'], PDO::PARAM_INT);
            $stmt->bindParam(':oficio_orgao', $dados['oficio_orgao'], PDO::PARAM_INT);
            $stmt->bindParam(':oficio_arquivo', $dados['oficio_arquivo'], PDO::PARAM_STR);
            $stmt->bindParam(':oficio_criado_por', $dados['oficio_criado_por'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            $this->logger->novoLog('oficio_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function AtualizarOficio($id, $dados) {
        try {
            if (isset($dados['oficio_arquivo']) && !empty($dados['oficio_arquivo'])) {
                $query = "UPDATE oficios SET oficio_titulo = :oficio_titulo, oficio_resumo = :oficio_resumo, oficio_ano = :oficio_ano, oficio_orgao = :oficio_orgao, oficio_arquivo = :oficio_arquivo WHERE oficio_id = :oficio_id;";
            } else {
                $query = "UPDATE oficios SET oficio_titulo = :oficio_titulo, oficio_resumo = :oficio_resumo, oficio_ano = :oficio_ano, oficio_orgao = :oficio_orgao WHERE oficio_id = :oficio_id;";
            }

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':oficio_titulo', $dados['oficio_titulo'], PDO::PARAM_STR);
            $stmt->bindParam(':oficio_resumo', $dados['oficio_resumo'], PDO::PARAM_STR);
            $stmt->bindParam(':oficio_ano', $dados['oficio_ano'], PDO::PARAM_INT);
            $stmt->bindParam(':oficio_orgao', $dados['oficio_orgao'], PDO::PARAM_INT);
            $stmt->bindParam(':oficio_id', $id, PDO::PARAM_INT);

            if (isset($dados['oficio_arquivo']) && !empty($dados['oficio_arquivo'])) {
                $stmt->bindParam(':oficio_arquivo', $dados['oficio_arquivo'], PDO::PARAM_STR);
            }

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('oficio_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ListarOficios($ano, $busca) {
        try {
            if ($busca === '') {
                $query = 'SELECT * FROM view_oficios WHERE oficio_ano = :ano ORDER BY oficio_titulo DESC';
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
            } else {
                $query = 'SELECT * FROM view_oficios WHERE oficio_titulo LIKE :busca OR oficio_resumo LIKE :busca ORDER BY oficio_ano DESC';
                $stmt = $this->db->prepare($query);
                $busca = '%' . $busca . '%';
                $stmt->bindValue(':busca', $busca, PDO::PARAM_STR);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                return ['status' => 'empty'];
            }

            return ['status' => 'success', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('oficio_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function BuscarOfício($coluna, $valor) {

        $query = "SELECT * FROM view_oficios WHERE $coluna = :valor";

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
            $this->logger->novoLog('user_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
        }
    }

    public function ApagarOficio($id) {

        $query = "DELETE FROM oficios WHERE oficio_id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }
}