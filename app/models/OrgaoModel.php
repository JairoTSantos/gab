<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class OrgaoModel {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }

    public function NovoOrgao($dados) {
        try {

            $query = "INSERT INTO orgaos (orgao_nome, orgao_email, orgao_telefone, orgao_endereco, orgao_bairro, orgao_municipio, orgao_estado, orgao_cep, orgao_tipo, orgao_informacoes, orgao_site, orgao_criado_por) VALUES (:orgao_nome, :orgao_email, :orgao_telefone, :orgao_endereco, :orgao_bairro, :orgao_municipio, :orgao_estado, :orgao_cep, :orgao_tipo, :orgao_informacoes, :orgao_site, :orgao_criado_por)";

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
            $this->logger->novoLog('orgao_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function AtualizarOrgao($orgao_id, $dados) {
        try {
            $query = "UPDATE orgaos SET orgao_nome = :orgao_nome, orgao_email = :orgao_email, orgao_telefone = :orgao_telefone, orgao_endereco = :orgao_endereco, orgao_bairro = :orgao_bairro, orgao_municipio = :orgao_municipio, orgao_estado = :orgao_estado, orgao_cep = :orgao_cep, orgao_tipo = :orgao_tipo, orgao_informacoes = :orgao_informacoes, orgao_site = :orgao_site, orgao_atualizado_em = CURRENT_TIMESTAMP WHERE orgao_id = :orgao_id";

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
            $stmt->bindParam(':orgao_id', $orgao_id, PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('orgao_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ApagarOrgao($id) {

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
            $this->logger->novoLog('orgao_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ListarOrgaos($itens, $pagina, $ordem, $ordenarPor, $termo, $filtro) {

        $config = require dirname(__DIR__) . '/config/config.php';
        $depConfig = $config['deputado'];

        $pagina = (int)$pagina;
        $itens = (int)$itens;
        $offset = ($pagina - 1) * $itens;


        if ($termo === null) {
            if ($filtro) {
                $query = "SELECT view_orgaos.*, (SELECT COUNT(*) FROM orgaos WHERE orgao_id <> 1000 AND orgao_estado = '".$depConfig['estado_deputado']."') AS total FROM view_orgaos WHERE orgao_id <> 1000 AND orgao_estado = '".$depConfig['estado_deputado']."' ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
            } else {
                $query = "SELECT view_orgaos.*, (SELECT COUNT(*) FROM orgaos WHERE orgao_id <> 1000) AS total FROM view_orgaos WHERE orgao_id <> 1000 ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
            }
        } else {
            if ($filtro) {
                $query = "SELECT view_orgaos.*, (SELECT COUNT(*) FROM orgaos WHERE orgao_id <> 1000 AND orgao_nome LIKE :termo AND orgao_estado = '".$depConfig['estado_deputado']."') AS total FROM view_orgaos WHERE orgao_id <> 1000 AND orgao_nome LIKE :termo AND orgao_estado = '".$depConfig['estado_deputado']."' ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
                $termo = '%' . $termo . '%';
            } else {
                $query = "SELECT view_orgaos.*, (SELECT COUNT(*) FROM orgaos WHERE orgao_id <> 1000 AND orgao_nome LIKE :termo) AS total FROM view_orgaos WHERE orgao_id <> 1000 AND orgao_nome LIKE :termo ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
                $termo = '%' . $termo . '%';
            }
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

    public function BuscarOrgao($coluna, $valor) {

        $query = "SELECT * FROM view_orgaos WHERE $coluna = :valor AND orgao_id <> 1000";

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
            $this->logger->novoLog('orgao_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
        }
    }
}
