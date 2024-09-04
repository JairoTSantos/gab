<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class PessoaModel {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function novaPessoa($dados) {
       
        $query = "INSERT INTO pessoas (pessoa_nome, pessoa_aniversario, pessoa_email, pessoa_telefone, pessoa_endereco, pessoa_bairro, pessoa_municipio, pessoa_estado, pessoa_cep, pessoa_sexo, pessoa_facebook, pessoa_instagram, pessoa_x, pessoa_informacoes, pessoa_profissao, pessoa_cargo, pessoa_tipo, pessoa_orgao, pessoa_criada_por) VALUES (:pessoa_nome, :pessoa_aniversario, :pessoa_email, :pessoa_telefone, :pessoa_endereco, :pessoa_bairro, :pessoa_municipio, :pessoa_estado, :pessoa_cep, :pessoa_sexo, :pessoa_facebook, :pessoa_instagram, :pessoa_x, :pessoa_informacoes, :pessoa_profissao, :pessoa_cargo, :pessoa_tipo, :pessoa_orgao, :pessoa_criada_por)";

        try {
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
            $stmt->bindParam(':pessoa_criada_por', $dados['pessoa_criada_por'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function atualizarPessoa($id, $dados) {
        $query = "UPDATE pessoas SET pessoa_nome = :pessoa_nome, pessoa_aniversario = :pessoa_aniversario, pessoa_email = :pessoa_email, pessoa_telefone = :pessoa_telefone, pessoa_endereco = :pessoa_endereco, pessoa_bairro = :pessoa_bairro, pessoa_municipio = :pessoa_municipio, pessoa_estado = :pessoa_estado, pessoa_cep = :pessoa_cep, pessoa_sexo = :pessoa_sexo, pessoa_facebook = :pessoa_facebook, pessoa_instagram = :pessoa_instagram, pessoa_x = :pessoa_x, pessoa_informacoes = :pessoa_informacoes, pessoa_profissao = :pessoa_profissao, pessoa_cargo = :pessoa_cargo, pessoa_tipo = :pessoa_tipo, pessoa_orgao = :pessoa_orgao WHERE pessoa_id = :pessoa_id";

        try {
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
            $stmt->bindParam(':pessoa_id', $id, PDO::PARAM_INT);

            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function apagarPessoa($id) {
        $query = "DELETE FROM pessoas WHERE pessoa_id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict'];
            }
            novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function listarPessoas($pagina, $itens, $ordernarPor, $order, $termo, $filtro) {

        $config = require dirname(__DIR__) . '/config/config.php';
        $depConfig = $config['deputado'];
        
        $ordernarPor = in_array($ordernarPor, ['pessoa_nome', 'pessoa_criada_por', 'pessoa_estado', 'pessoa_municipio']) ? $ordernarPor : 'pessoa_nome';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        $pagina = (int)$pagina;
        $itens = (int)$itens;
        $offset = ($pagina - 1) * $itens;


        if($filtro){
            if ($termo === null) {
                $query = "SELECT view_pessoas.*, (SELECT COUNT(*) FROM pessoas WHERE pessoa_id <> 1000 AND pessoa_estado = '".$depConfig['estado_deputado']."') AS total FROM view_pessoas WHERE pessoa_id <> 1000 AND pessoa_estado = '".$depConfig['estado_deputado']."' ORDER BY $ordernarPor $order LIMIT :offset, :itens";
            } else {
                $query = "SELECT view_pessoas.*, (SELECT COUNT(*) FROM pessoas WHERE pessoa_id <> 1000 AND pessoa_nome LIKE :termo  AND pessoa_estado = '".$depConfig['estado_deputado']."') AS total FROM view_pessoas WHERE pessoa_id <> 1000 AND pessoa_nome LIKE :termo  AND pessoa_estado = '".$depConfig['estado_deputado']."' ORDER BY $ordernarPor $order LIMIT :offset, :itens";
                $termo = '%' . $termo . '%';
            }
        }else{
            if ($termo === null) {
                $query = "SELECT view_pessoas.*, (SELECT COUNT(*) FROM pessoas WHERE pessoa_id <> 1000) AS total FROM view_pessoas WHERE pessoa_id <> 1000 ORDER BY $ordernarPor $order LIMIT :offset, :itens";
            } else {
                $query = "SELECT view_pessoas.*, (SELECT COUNT(*) FROM pessoas WHERE pessoa_id <> 1000 AND pessoa_nome LIKE :termo) AS total FROM view_pessoas WHERE pessoa_id <> 1000 AND pessoa_nome LIKE :termo ORDER BY $ordernarPor $order LIMIT :offset, :itens";
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
            novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }


    public function buscarPessoa($coluna, $valor) {
        $coluna = in_array($coluna, ['pessoa_id', 'pessoa_email']) ? $coluna : 'pessoa_id';

        $query = "SELECT * FROM view_pessoas WHERE $coluna = :valor AND pessoa_id <> 1000";

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
            novoLog('pessoa_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
        }
    }



    public function novoTipoPessoa($dados) {
        $query = "INSERT INTO pessoas_tipos (pessoa_tipo_nome, pessoa_tipo_descricao, pessoa_tipo_criado_por) VALUES (:pessoa_tipo_nome, :pessoa_tipo_descricao, :pessoa_tipo_criado_por)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':pessoa_tipo_nome', $dados['pessoa_tipo_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_tipo_descricao', $dados['pessoa_tipo_descricao'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoa_tipo_criado_por', $dados['pessoa_tipo_criado_por'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            novoLog('pessoa_tipo_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function listarTiposPessoas() {
        $query = "SELECT * FROM pessoas_tipos INNER JOIN usuarios ON pessoas_tipos.pessoa_tipo_criado_por = usuarios.usuario_id WHERE pessoa_tipo_id <> 1000 ORDER BY pessoas_tipos.pessoa_tipo_nome;";

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
            novoLog('pessoa_tipo_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function apagarTipoPessoa($id) {
        $query = "DELETE FROM pessoas_tipos WHERE pessoa_tipo_id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict'];
            }
            novoLog('pessoa_tipo_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }



    public function novaProfissaoPessoa($dados) {
        $query = "INSERT INTO pessoas_profissoes (pessoas_profissoes_nome, pessoas_profissoes_descricao, pessoas_profissoes_criado_por) VALUES (:pessoas_profissoes_nome, :pessoas_profissoes_descricao, :pessoas_profissoes_criado_por)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':pessoas_profissoes_nome', $dados['pessoas_profissoes_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoas_profissoes_descricao', $dados['pessoas_profissoes_descricao'], PDO::PARAM_STR);
            $stmt->bindParam(':pessoas_profissoes_criado_por', $dados['pessoas_profissoes_criado_por'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return ['status' => 'duplicated'];
            }
            novoLog('pessoas_profissoes_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function listarProfissoesPessoas() {
        $query = "SELECT * FROM pessoas_profissoes INNER JOIN usuarios ON pessoas_profissoes.pessoas_profissoes_criado_por = usuarios.usuario_id ORDER BY pessoas_profissoes_nome";

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
            novoLog('pessoas_profissoes_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function apagarProfissaoPessoa($id) {
        $query = "DELETE FROM pessoas_profissoes WHERE pessoas_profissoes_id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
                return ['status' => 'delete_conflict'];
            }
            novoLog('pessoas_profissoes_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }
}
