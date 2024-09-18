<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class PostagemModel {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }

    public function NovaPostagem($dados) {
        try {
            $query = "INSERT INTO postagens (postagem_titulo, postagem_data, postagem_pasta, postagem_informacoes, postagem_midias, postagem_status, postagem_criada_por) 
                      VALUES (:postagem_titulo, :postagem_data, :postagem_pasta, :postagem_informacoes, :postagem_midias, :postagem_status, :postagem_criada_por)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':postagem_titulo', $dados['postagem_titulo'], PDO::PARAM_STR);
            $stmt->bindParam(':postagem_data', $dados['postagem_data'], PDO::PARAM_STR);
            $stmt->bindParam(':postagem_pasta', $dados['postagem_pasta'], PDO::PARAM_STR);
            $stmt->bindParam(':postagem_informacoes', $dados['postagem_informacoes'], PDO::PARAM_STR);
            $stmt->bindParam(':postagem_midias', $dados['postagem_midias'], PDO::PARAM_STR);
            $stmt->bindParam(':postagem_status', $dados['postagem_status'], PDO::PARAM_INT);
            $stmt->bindParam(':postagem_criada_por', $dados['postagem_criada_por'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            $this->logger->novoLog('postagem_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function AtualizarPostagem($id, $dados) {
        try {
            $query = "UPDATE postagens 
                      SET postagem_titulo = :postagem_titulo, postagem_data = :postagem_data, postagem_pasta = :postagem_pasta, 
                          postagem_informacoes = :postagem_informacoes, postagem_midias = :postagem_midias, postagem_status = :postagem_status
                      WHERE postagem_id = :postagem_id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':postagem_titulo', $dados['postagem_titulo'], PDO::PARAM_STR);
            $stmt->bindParam(':postagem_data', $dados['postagem_data'], PDO::PARAM_STR);
            $stmt->bindParam(':postagem_pasta', $dados['postagem_pasta'], PDO::PARAM_STR);
            $stmt->bindParam(':postagem_informacoes', $dados['postagem_informacoes'], PDO::PARAM_STR);
            $stmt->bindParam(':postagem_midias', $dados['postagem_midias'], PDO::PARAM_STR);
            $stmt->bindParam(':postagem_status', $dados['postagem_status'], PDO::PARAM_INT);
            $stmt->bindParam(':postagem_id', $id, PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('postagem_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function BuscarPostagem($coluna, $valor) {
        $query = "SELECT * FROM view_postagens WHERE $coluna = :valor";

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
            $this->logger->novoLog('postagem_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ListarPostagens($itens, $pagina, $ordem, $ordenarPor) {
        $pagina = (int)$pagina;
        $itens = (int)$itens;
        $offset = ($pagina - 1) * $itens;

        $query = "SELECT view_postagens.*, (SELECT COUNT(postagem_id) FROM postagens) AS total 
                  FROM view_postagens 
                  ORDER BY $ordenarPor $ordem 
                  LIMIT :offset, :itens";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':itens', $itens, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                return ['status' => 'empty'];
            }

            $total = $result[0]['total'];
            $totalPaginas = ceil($total / $itens);

            return [
                'status' => 'success',
                'dados' => $result,
                'total_paginas' => $totalPaginas
            ];
        } catch (PDOException $e) {
            $this->logger->novoLog('postagem_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ApagarPostagem($id) {
        $query = "DELETE FROM postagens WHERE postagem_id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict'];
            }
            $this->logger->novoLog('postagem_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }
}
