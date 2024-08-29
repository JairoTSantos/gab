<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class OrgaoModel {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function novoOrgao($dados) {

        $query = "INSERT INTO orgaos (orgao_nome, orgao_email, orgao_telefone, orgao_endereco, orgao_bairro, orgao_municipio, orgao_estado, orgao_cep, orgao_tipo, orgao_informacoes, orgao_site, orgao_criado_por) VALUE (:orgao_nome, :orgao_email, :orgao_telefone, :orgao_endereco, :orgao_bairro, :orgao_municipio, :orgao_estado, :orgao_cep, :orgao_tipo, :orgao_informacoes, :orgao_site, :orgao_criado_por);";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':orgao_nome', $dados['orgao_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_email', $dados['orgao_email'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_telefone', $dados['orgao_telefone'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_endereco', $dados['orgao_endereco'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_bairro', $dados['orgao_bairro'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_municipio', $dados['orgao_municipio'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_estado', $dados['orgao_estado'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_cep', $dados['orgao_cep'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_tipo', $dados['orgao_tipo'], PDO::PARAM_INT);
            $stmt->bindParam(':orgao_informacoes', $dados['orgao_informacoes'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_site', $dados['orgao_site'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_criado_por', $dados['orgao_criado_por'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            novoLog('orgao_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function atualizarOrgao($id, $dados) {

        $query = "UPDATE orgaos SET orgao_nome = :orgao_nome, orgao_email = :orgao_email, orgao_telefone = :orgao_telefone, orgao_endereco = :orgao_endereco, orgao_bairro = :orgao_bairro, orgao_municipio = :orgao_municipio, orgao_estado = :orgao_estado, orgao_cep = :orgao_cep, orgao_tipo = :orgao_tipo, orgao_informacoes = :orgao_informacoes, orgao_site = :orgao_site WHERE orgao_id = :orgao_id;";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':orgao_nome', $dados['orgao_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_email', $dados['orgao_email'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_telefone', $dados['orgao_telefone'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_endereco', $dados['orgao_endereco'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_bairro', $dados['orgao_bairro'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_municipio', $dados['orgao_municipio'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_estado', $dados['orgao_estado'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_cep', $dados['orgao_cep'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_tipo', $dados['orgao_tipo'], PDO::PARAM_INT);
            $stmt->bindParam(':orgao_informacoes', $dados['orgao_informacoes'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_site', $dados['orgao_site'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_id', $id, PDO::PARAM_INT);

            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            novoLog('orgao_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function apagarOrgao($id) {

        $query = "DELETE FROM orgaos WHERE orgao_id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict'];
            }
            novoLog('orgao_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function listarOrgaos($pagina = 1, $itens = 10, $ordernarPor, $order) {
        $ordernarPor = in_array($ordernarPor, ['orgao_nome', 'orgao_criado_por', 'orgao_estado', 'orgao_municipio']) ? $ordernarPor : 'orgao_nome';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        $pagina = (int)$pagina;
        $itens = (int)$itens;
        $offset = ($pagina - 1) * $itens;

        $query = "SELECT view_orgaos.*, (SELECT COUNT(*) FROM orgaos WHERE orgao_id <> 1000) AS total FROM view_orgaos WHERE orgao_id <> 1000 ORDER BY $ordernarPor $order LIMIT :offset, :itens";

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
            novoLog('orgao_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function buscarOrgao($coluna, $valor) {

        $coluna = in_array($coluna, ['orgao_id', 'orgao_email']) ? $coluna : 'orgao_id';

        $query = "SELECT * FROM view_orgaos WHERE $coluna = :valor AND orgao_id <> 1000";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':valor', $valor, PDO::PARAM_INT);
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
            novoLog('orgao_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
        }
    }




    
    public function novoTipoOrgao($dados) {

        $query = "INSERT INTO orgaos_tipos (orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUE (:orgao_tipo_nome, :orgao_tipo_descricao, :orgao_tipo_criado_por);";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':orgao_tipo_nome', $dados['orgao_tipo_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_tipo_descricao', $dados['orgao_tipo_descricao'], PDO::PARAM_STR);
            $stmt->bindParam(':orgao_tipo_criado_por', $dados['orgao_tipo_criado_por'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            novoLog('orgao_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function listarTiposOrgaos() {
        $query = "SELECT * FROM orgaos_tipos INNER JOIN usuarios ON orgaos_tipos.orgao_tipo_criado_por = usuarios.usuario_id ORDER BY orgao_tipo_nome";

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
            novoLog('orgao_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function apagarTipoOrgao($id) {

        $query = "DELETE FROM orgaos_tipos WHERE orgao_tipo_id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict'];
            }
            novoLog('orgao_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }
}
