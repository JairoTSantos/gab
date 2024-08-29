<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class UsuarioModel {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function novoUsuario($dados) {

        $query = "INSERT INTO usuarios (usuario_nome, usuario_email, usuario_telefone, usuario_senha, usuario_nivel, usuario_ativo, usuario_aniversario) VALUES (:usuario_nome, :usuario_email, :usuario_telefone, :usuario_senha, :usuario_nivel, :usuario_ativo, :usuario_aniversario)";

        $senhaCriptografada = password_hash($dados['usuario_senha'], PASSWORD_BCRYPT);

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':usuario_nome', $dados['usuario_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_email', $dados['usuario_email'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_telefone', $dados['usuario_telefone'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_senha', $senhaCriptografada, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_nivel', $dados['usuario_nivel'], PDO::PARAM_INT);
            $stmt->bindParam(':usuario_ativo', $dados['usuario_ativo'], PDO::PARAM_INT);
            $stmt->bindParam(':usuario_aniversario', $dados['usuario_aniversario'], PDO::PARAM_STR);
            
            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            novoLog('usuario_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function atualizarUsuario($id, $dados) {

        $query = "UPDATE usuarios SET usuario_nome = :usuario_nome, usuario_email = :usuario_email, usuario_telefone = :usuario_telefone, usuario_nivel = :usuario_nivel, usuario_ativo = :usuario_ativo, usuario_aniversario = :usuario_aniversario WHERE usuario_id = :usuario_id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':usuario_nome', $dados['usuario_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_email', $dados['usuario_email'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_telefone', $dados['usuario_telefone'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_nivel', $dados['usuario_nivel'], PDO::PARAM_INT);
            $stmt->bindParam(':usuario_ativo', $dados['usuario_ativo'], PDO::PARAM_INT);
            $stmt->bindParam(':usuario_aniversario', $dados['usuario_aniversario'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            novoLog('usuario_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function apagarUsuario($id) {

        $query = "DELETE FROM usuarios WHERE usuario_id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict'];
            }
            novoLog('usuario_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function listarUsuarios($pagina = 1, $itens = 10, $ordernarPor, $order) {
        $ordernarPor = in_array($ordernarPor, ['usuario_nome', 'usuario_criado_em', 'usuario_ativo', 'usuario_nivel']) ? $ordernarPor : 'usuario_nome';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        $pagina = (int)$pagina;
        $itens = (int)$itens;
        $offset = ($pagina - 1) * $itens;

        $query = "SELECT usuarios.*, (SELECT COUNT(*) FROM usuarios WHERE usuario_id <> 1000) AS total FROM usuarios WHERE usuario_id <> 1000 ORDER BY $ordernarPor $order LIMIT :offset, :itens";

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
            novoLog('usuario_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function buscarUsuario($coluna, $valor) {

        $coluna = in_array($coluna, ['usuario_id', 'usuario_email']) ? $coluna : 'usuario_id';

        $query = "SELECT * FROM usuarios WHERE $coluna = :valor AND usuario_id <> 1000";

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
            novoLog('usuario_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
        }
    }
}
