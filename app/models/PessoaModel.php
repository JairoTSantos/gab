<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class PessoaModel {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }


    public function NovaPessoa($dados) {
        try {
            $query = "INSERT INTO pessoas (pessoa_nome, pessoa_aniversario, pessoa_email, pessoa_telefone, pessoa_endereco, pessoa_bairro, pessoa_municipio, pessoa_estado, pessoa_cep, pessoa_sexo, pessoa_facebook, pessoa_instagram, pessoa_x, pessoa_informacoes, pessoa_profissao, pessoa_cargo, pessoa_tipo, pessoa_orgao, pessoa_foto, pessoa_criada_por) VALUES (:pessoa_nome, :pessoa_aniversario, :pessoa_email, :pessoa_telefone, :pessoa_endereco, :pessoa_bairro, :pessoa_municipio, :pessoa_estado, :pessoa_cep, :pessoa_sexo, :pessoa_facebook, :pessoa_instagram, :pessoa_x, :pessoa_informacoes, :pessoa_profissao, :pessoa_cargo, :pessoa_tipo, :pessoa_orgao, :pessoa_foto, :pessoa_criada_por)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':pessoa_nome', $dados['pessoa_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_aniversario', $dados['pessoa_aniversario'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_email', $dados['pessoa_email'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_telefone', $dados['pessoa_telefone'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_endereco', $dados['pessoa_endereco'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_bairro', $dados['pessoa_bairro'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_municipio', $dados['pessoa_municipio'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_estado', $dados['pessoa_estado'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_cep', $dados['pessoa_cep'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_sexo', $dados['pessoa_sexo'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_facebook', $dados['pessoa_facebook'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_instagram', $dados['pessoa_instagram'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_x', $dados['pessoa_x'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_informacoes', $dados['pessoa_informacoes'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_profissao', $dados['pessoa_profissao'], PDO::PARAM_INT);
            $stmt->bindParam(':pessoa_cargo', $dados['pessoa_cargo'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_tipo', $dados['pessoa_tipo'], PDO::PARAM_INT);
            $stmt->bindParam(':pessoa_orgao', $dados['pessoa_orgao'], PDO::PARAM_INT);
            $stmt->bindParam(':pessoa_foto', $dados['pessoa_foto'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_criada_por', $dados['pessoa_criada_por'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }


    public function AtualizarPessoa($pessoa_id, $dados) {
        try {
            $query = "UPDATE pessoas SET pessoa_nome = :pessoa_nome, pessoa_aniversario = :pessoa_aniversario, pessoa_email = :pessoa_email, pessoa_telefone = :pessoa_telefone, pessoa_endereco = :pessoa_endereco, pessoa_bairro = :pessoa_bairro, pessoa_municipio = :pessoa_municipio, pessoa_estado = :pessoa_estado, pessoa_cep = :pessoa_cep, pessoa_sexo = :pessoa_sexo, pessoa_facebook = :pessoa_facebook, pessoa_instagram = :pessoa_instagram, pessoa_x = :pessoa_x, pessoa_informacoes = :pessoa_informacoes, pessoa_profissao = :pessoa_profissao, pessoa_cargo = :pessoa_cargo, pessoa_tipo = :pessoa_tipo, pessoa_orgao = :pessoa_orgao, pessoa_foto = :pessoa_foto, pessoa_atualizada_em = CURRENT_TIMESTAMP WHERE pessoa_id = :pessoa_id";

            $stmt = $this->db->prepare($query);
    
            $stmt->bindParam(':pessoa_nome', $dados['pessoa_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_aniversario', $dados['pessoa_aniversario'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_email', $dados['pessoa_email'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_telefone', $dados['pessoa_telefone'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_endereco', $dados['pessoa_endereco'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_bairro', $dados['pessoa_bairro'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_municipio', $dados['pessoa_municipio'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_estado', $dados['pessoa_estado'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_cep', $dados['pessoa_cep'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_sexo', $dados['pessoa_sexo'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_facebook', $dados['pessoa_facebook'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_instagram', $dados['pessoa_instagram'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_x', $dados['pessoa_x'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_informacoes', $dados['pessoa_informacoes'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_profissao', $dados['pessoa_profissao'], PDO::PARAM_INT);
            $stmt->bindParam(':pessoa_cargo', $dados['pessoa_cargo'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_tipo', $dados['pessoa_tipo'], PDO::PARAM_INT);
            $stmt->bindParam(':pessoa_orgao', $dados['pessoa_orgao'], PDO::PARAM_INT);
            $stmt->bindParam(':pessoa_foto', $dados['pessoa_foto'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_id', $pessoa_id, PDO::PARAM_INT);
    
            $stmt->execute();
    
            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ApagarPessoa($id) {

        $query = "DELETE FROM pessoas WHERE pessoa_id = :id";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict']; // Conflito de chave estrangeira
            }
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }


    public function BuscarPessoa($coluna, $valor) {

        $query = "SELECT * FROM view_pessoas WHERE $coluna = :valor";
    
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
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
        }
    }

    public function ListarPessoas($itens, $pagina, $ordem, $ordenarPor) {

        $pagina = (int)$pagina;
        $itens = (int)$itens;
        $offset = ($pagina - 1) * $itens;

        $query = "SELECT view_pessoas.*, (SELECT COUNT(pessoa_id) FROM view_pessoas) AS total 
                  FROM view_pessoas
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
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
        }
    }
    
    
    
}