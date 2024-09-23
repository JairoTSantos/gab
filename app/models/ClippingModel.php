<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class ClippingModel {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }

    public function NovoClipping($dados) {
        try {
            $query = "INSERT INTO clipping (clipping_resumo, clipping_link, clipping_orgao, clipping_arquivo, clipping_tipo, clipping_criado_por) 
                      VALUES (:clipping_resumo, :clipping_link, :clipping_orgao, :clipping_arquivo, :clipping_tipo, :clipping_criado_por)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':clipping_resumo', $dados['clipping_resumo'], PDO::PARAM_STR);
            $stmt->bindParam(':clipping_link', $dados['clipping_link'], PDO::PARAM_STR);
            $stmt->bindParam(':clipping_orgao', $dados['clipping_orgao'], PDO::PARAM_INT);
            $stmt->bindParam(':clipping_arquivo', $dados['clipping_arquivo'], PDO::PARAM_STR);
            $stmt->bindParam(':clipping_tipo', $dados['clipping_tipo'], PDO::PARAM_INT);
            $stmt->bindParam(':clipping_criado_por', $dados['clipping_criado_por'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            $this->logger->novoLog('clipping_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function AtualizarClipping($clipping_id, $dados) {
        try {
            $query = "UPDATE clipping 
                      SET clipping_resumo = :clipping_resumo, 
                          clipping_link = :clipping_link, 
                          clipping_orgao = :clipping_orgao, 
                          clipping_arquivo = :clipping_arquivo, 
                          clipping_tipo = :clipping_tipo 
                      WHERE clipping_id = :clipping_id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':clipping_resumo', $dados['clipping_resumo'], PDO::PARAM_STR);
            $stmt->bindParam(':clipping_link', $dados['clipping_link'], PDO::PARAM_STR);
            $stmt->bindParam(':clipping_orgao', $dados['clipping_orgao'], PDO::PARAM_INT);
            $stmt->bindParam(':clipping_arquivo', $dados['clipping_arquivo'], PDO::PARAM_STR);
            $stmt->bindParam(':clipping_tipo', $dados['clipping_tipo'], PDO::PARAM_INT);
            $stmt->bindParam(':clipping_id', $clipping_id, PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('clipping_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ApagarClipping($clipping_id) {
        try {
            $query = "DELETE FROM clipping WHERE clipping_id = :clipping_id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':clipping_id', $clipping_id, PDO::PARAM_INT);
            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict']; // Conflito de chave estrangeira
            }
            $this->logger->novoLog('clipping_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function BuscarClipping($coluna, $valor) {
        $query = "SELECT * FROM view_clipping WHERE $coluna = :valor";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':valor', $valor);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (empty($result)) {
                return ['status' => 'empty'];
            }
            return ['status' => 'success', 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('clipping_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ListarClippings($itens, $pagina, $ordem, $ordenarPor, $termo = null) {
        $pagina = (int)$pagina;
        $itens = (int)$itens;
        $offset = ($pagina - 1) * $itens;

        if ($termo === null) {
            $query = "SELECT view_clipping.*, (SELECT COUNT(*) FROM clipping) AS total 
                      FROM view_clipping 
                      ORDER BY $ordenarPor $ordem 
                      LIMIT :offset, :itens";
        } else {
            $query = "SELECT view_clipping.*, (SELECT COUNT(*) FROM clipping WHERE clipping_resumo LIKE :termo) AS total 
                      FROM view_clipping 
                      WHERE clipping_resumo LIKE :termo 
                      ORDER BY $ordenarPor $ordem 
                      LIMIT :offset, :itens";
            $termo = '%' . $termo . '%';
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':itens', $itens, PDO::PARAM_INT);

            if ($termo !== null) {
                $stmt->bindValue(':termo', $termo, PDO::PARAM_STR);
            }

            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                return ['status' => 'empty'];
            }

            $total = $result[0]['total'];
            $totalPaginas = ceil($total / $itens);

            return ['status' => 'success', 'dados' => $result, 'total_paginas' => $totalPaginas];
        } catch (PDOException $e) {
            $this->logger->novoLog('clipping_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }
}
