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

            if (isset($dados['pessoa_foto']) && !empty($dados['pessoa_foto'])) {
                $query = "UPDATE pessoas SET pessoa_nome = :pessoa_nome, pessoa_aniversario = :pessoa_aniversario, pessoa_email = :pessoa_email, pessoa_telefone = :pessoa_telefone, pessoa_endereco = :pessoa_endereco, pessoa_bairro = :pessoa_bairro, pessoa_municipio = :pessoa_municipio, pessoa_estado = :pessoa_estado, pessoa_cep = :pessoa_cep, pessoa_sexo = :pessoa_sexo, pessoa_facebook = :pessoa_facebook, pessoa_instagram = :pessoa_instagram, pessoa_x = :pessoa_x, pessoa_informacoes = :pessoa_informacoes, pessoa_profissao = :pessoa_profissao, pessoa_cargo = :pessoa_cargo, pessoa_tipo = :pessoa_tipo, pessoa_orgao = :pessoa_orgao, pessoa_foto = :pessoa_foto WHERE pessoa_id = :pessoa_id";
            } else {
                $query = "UPDATE pessoas SET pessoa_nome = :pessoa_nome, pessoa_aniversario = :pessoa_aniversario, pessoa_email = :pessoa_email, pessoa_telefone = :pessoa_telefone, pessoa_endereco = :pessoa_endereco, pessoa_bairro = :pessoa_bairro, pessoa_municipio = :pessoa_municipio, pessoa_estado = :pessoa_estado, pessoa_cep = :pessoa_cep, pessoa_sexo = :pessoa_sexo, pessoa_facebook = :pessoa_facebook, pessoa_instagram = :pessoa_instagram, pessoa_x = :pessoa_x, pessoa_informacoes = :pessoa_informacoes, pessoa_profissao = :pessoa_profissao, pessoa_cargo = :pessoa_cargo, pessoa_tipo = :pessoa_tipo, pessoa_orgao = :pessoa_orgao WHERE pessoa_id = :pessoa_id";
            }

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
            $stmt->bindParam(':pessoa_id', $pessoa_id, PDO::PARAM_INT);

            if (isset($dados['pessoa_foto']) && !empty($dados['pessoa_foto'])) {
                $stmt->bindParam(':pessoa_foto', $dados['pessoa_foto'], PDO::PARAM_STR);
            }

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

    public function BuscarAniversariante($mes, $dia) {
        if ($dia !== null) {
            $query = "SELECT * FROM view_pessoas WHERE MONTH(pessoa_aniversario) = :mes AND DAY(pessoa_aniversario) = :dia ORDER BY DAY(pessoa_aniversario) ASC";
        } else {
            $query = "SELECT * FROM view_pessoas WHERE MONTH(pessoa_aniversario) = :mes ORDER BY DAY(pessoa_aniversario) ASC";
        }
    
        try {
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':mes', $mes, PDO::PARAM_INT);
    
            if ($dia !== null) {
                $stmt->bindParam(':dia', $dia, PDO::PARAM_INT);
            }
            
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
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
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

    public function ListarPessoas($itens, $pagina, $ordem, $ordenarPor, $termo, $filtro) {

   

        $config = require dirname(__DIR__) . '/config/config.php';
        $depConfig = $config['deputado'];

        $pagina = (int)$pagina;
        $itens = (int)$itens;
        $offset = ($pagina - 1) * $itens;


        if ($termo === null) {
            if ($filtro) {
                $query = "SELECT view_pessoas.*, (SELECT COUNT(*) FROM pessoas WHERE pessoa_id <> 1000 AND pessoa_estado = '" . $depConfig['estado_deputado'] . "') AS total FROM view_pessoas WHERE pessoa_id <> 1000 AND pessoa_estado = '" . $depConfig['estado_deputado'] . "' ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
            } else {
                $query = "SELECT view_pessoas.*, (SELECT COUNT(*) FROM pessoas WHERE pessoa_id <> 1000) AS total FROM view_pessoas WHERE pessoa_id <> 1000 ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
            }
        } else {
            if ($filtro) {
                $query = "SELECT view_pessoas.*, (SELECT COUNT(*) FROM pessoas WHERE pessoa_id <> 1000 AND pessoa_nome LIKE :termo AND pessoa_estado = '" . $depConfig['estado_deputado'] . "') AS total FROM view_pessoas WHERE pessoa_id <> 1000 AND pessoa_nome LIKE :termo AND pessoa_estado = '" . $depConfig['estado_deputado'] . "' ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
                $termo = '%' . $termo . '%';
            } else {
                $query = "SELECT view_pessoas.*, (SELECT COUNT(*) FROM pessoas WHERE pessoa_id <> 1000 AND pessoa_nome LIKE :termo) AS total FROM view_pessoas WHERE pessoa_id <> 1000 AND pessoa_nome LIKE :termo ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
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
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
        }
    }
}
