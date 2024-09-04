<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class NotaModel {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function novaNota($dados) {

        $query = "INSERT INTO notas_tecnicas (nota_proposicao, nota_titulo, nota_resumo, nota_texto, nota_criada_por) VALUES (:nota_proposicao, :nota_titulo, :nota_resumo, :nota_texto, :nota_criada_por)";

        try {
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
            novoLog('nota_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function atualizarNota($id, $dados) {

        $query = "UPDATE notas_tecnicas SET nota_proposicao = :nota_proposicao, nota_titulo = :nota_titulo, nota_resumo = :nota_resumo, nota_texto = :nota_texto WHERE nota_id = :nota_id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nota_proposicao', $dados['nota_proposicao'], PDO::PARAM_INT);
            $stmt->bindParam(':nota_titulo', $dados['nota_titulo'], PDO::PARAM_STR);
            $stmt->bindParam(':nota_resumo', $dados['nota_resumo'], PDO::PARAM_STR);
            $stmt->bindParam(':nota_texto', $dados['nota_texto'], PDO::PARAM_STR);
            $stmt->bindParam(':nota_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            novoLog('nota_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function apagarNota($id) {

        $query = "DELETE FROM notas_tecnicas WHERE nota_id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict'];
            }
            novoLog('nota_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function listarNotas($pagina = 1, $itens = 10, $ordernarPor, $order) {
        $ordernarPor = in_array($ordernarPor, ['nota_titulo', 'nota_criada_em', 'nota_atualizada_em']) ? $ordernarPor : 'nota_criada_em';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        $pagina = (int)$pagina;
        $itens = (int)$itens;
        $offset = ($pagina - 1) * $itens;

        $query = "SELECT view_notas_tecnicas.*, COUNT(*) OVER() AS total FROM view_notas_tecnicas ORDER BY $ordernarPor $order LIMIT :offset, :itens";

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
            novoLog('nota_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function buscarNota($coluna, $valor) {

        $coluna = in_array($coluna, ['nota_id', 'nota_proposicao']) ? $coluna : 'nota_id';

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
            novoLog('nota_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }
}
