<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class NotaTecnicaModel {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }

    public function NovaNotaTecnica($dados) {
        try {
            $query = "INSERT INTO notas_tecnicas (nota_proposicao, nota_titulo, nota_resumo, nota_texto, nota_criada_por) 
                      VALUES (:nota_proposicao, :nota_titulo, :nota_resumo, :nota_texto, :nota_criada_por)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':nota_proposicao', $dados['nota_proposicao'], PDO::PARAM_INT);
            $stmt->bindParam(':nota_titulo', $dados['nota_titulo'], PDO::PARAM_STR);
            $stmt->bindParam(':nota_resumo', $dados['nota_resumo'], PDO::PARAM_STR);
            $stmt->bindParam(':nota_texto', $dados['nota_texto'], PDO::PARAM_STR);
            $stmt->bindParam(':nota_criada_por', $dados['nota_criada_por'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            $this->logger->novoLog('notas_tecnicas_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function AtualizarNotaTecnica($id, $dados) {
        try {
            $query = "UPDATE notas_tecnicas SET  
                      nota_titulo = :nota_titulo, 
                      nota_resumo = :nota_resumo, 
                      nota_texto = :nota_texto 
                      WHERE nota_proposicao = :nota_proposicao";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':nota_titulo', $dados['nota_titulo'], PDO::PARAM_STR);
            $stmt->bindParam(':nota_resumo', $dados['nota_resumo'], PDO::PARAM_STR);
            $stmt->bindParam(':nota_texto', $dados['nota_texto'], PDO::PARAM_STR);
            $stmt->bindParam(':nota_proposicao', $id, PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('notas_tecnicas_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ApagarNotaTecnica($id) {
        $query = "DELETE FROM notas_tecnicas WHERE nota_proposicao = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict'];
            }
            $this->logger->novoLog('notas_tecnicas_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ListarNotasTecnicas() {
        $query = "SELECT * FROM notas_tecnicas ORDER BY nota_titulo ASC";

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
            $this->logger->novoLog('notas_tecnicas_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function BuscarNotaTecnica($coluna, $valor) {
        $query = "SELECT * FROM view_notas_tecnicas WHERE $coluna = :valor";

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
            $this->logger->novoLog('notas_tecnicas_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }
}
