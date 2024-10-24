<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class UsuarioModel {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }

    public function NovoUsuario($dados) {
        try {

            $query = "INSERT INTO usuarios (usuario_nome, usuario_email, usuario_telefone, usuario_senha, usuario_nivel, usuario_ativo, usuario_aniversario, usuario_foto) VALUES (:usuario_nome, :usuario_email, :usuario_telefone, :usuario_senha, :usuario_nivel, :usuario_ativo, :usuario_aniversario, :usuario_foto)";

            $stmt = $this->db->prepare($query);

            $senhaCriptografada = password_hash($dados['usuario_senha'], PASSWORD_BCRYPT);

            $stmt->bindParam(':usuario_nome', $dados['usuario_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_email', $dados['usuario_email'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_telefone', $dados['usuario_telefone'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_senha', $senhaCriptografada, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_nivel', $dados['usuario_nivel'], PDO::PARAM_INT);
            $stmt->bindParam(':usuario_ativo', $dados['usuario_ativo'], PDO::PARAM_INT);
            $stmt->bindParam(':usuario_aniversario', $dados['usuario_aniversario'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_foto', $dados['usuario_foto'], PDO::PARAM_STR);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function AtualizarUsuario($id, $dados) {
        try {
            if (isset($dados['usuario_foto']) && !empty($dados['usuario_foto'])) {
                $query = "UPDATE usuarios SET usuario_nome = :usuario_nome, usuario_email = :usuario_email, usuario_telefone = :usuario_telefone, usuario_nivel = :usuario_nivel, usuario_ativo = :usuario_ativo, usuario_aniversario = :usuario_aniversario, usuario_foto = :usuario_foto WHERE usuario_id = :usuario_id";
            } else {
                $query = "UPDATE usuarios SET usuario_nome = :usuario_nome, usuario_email = :usuario_email, usuario_telefone = :usuario_telefone, usuario_nivel = :usuario_nivel, usuario_ativo = :usuario_ativo, usuario_aniversario = :usuario_aniversario WHERE usuario_id = :usuario_id";
            }

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':usuario_nome', $dados['usuario_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_email', $dados['usuario_email'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_telefone', $dados['usuario_telefone'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_nivel', $dados['usuario_nivel'], PDO::PARAM_INT);
            $stmt->bindParam(':usuario_ativo', $dados['usuario_ativo'], PDO::PARAM_INT);
            $stmt->bindParam(':usuario_aniversario', $dados['usuario_aniversario'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_id', $id, PDO::PARAM_INT);

            if (isset($dados['usuario_foto']) && !empty($dados['usuario_foto'])) {
                $stmt->bindParam(':usuario_foto', $dados['usuario_foto'], PDO::PARAM_STR);
            }

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function BuscarUsuario($coluna, $valor) {

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
            $this->logger->novoLog('user_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
        }
    }

    public function ListarUsuarios($itens, $pagina, $ordem, $ordenarPor) {

        $pagina = (int)$pagina;
        $itens = (int)$itens;
        $offset = ($pagina - 1) * $itens;

        $query = "SELECT usuarios.*, (SELECT COUNT(usuario_id) FROM usuarios WHERE usuario_id <> 1000) AS total 
                  FROM usuarios 
                  WHERE usuario_id <> 1000 
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
            $this->logger->novoLog('user_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
        }
    }

    public function ApagarUsuario($id) {

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
            $this->logger->novoLog('user_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }
}
