<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class ProposicaoModel {

    private $db;
    private $logger;
    private $config;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
        $this->config = require dirname(__DIR__) . '/config/config.php';
    }

    public function limparBanco($tabela, $ano) {
        try {
            $query = "DELETE FROM $tabela WHERE proposicao_ano = :ano";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':ano', $ano, PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('proposicoes_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }


    public function InserirProposicao($dados) {
        try {

            $query = "INSERT INTO proposicoes (proposicao_id, proposicao_titulo, proposicao_sigla, proposicao_numero, proposicao_ano, proposicao_ementa, proposicao_apresentacao, proposicao_arquivada, proposicao_norma) VALUES (:proposicao_id, :proposicao_titulo, :proposicao_sigla, :proposicao_numero, :proposicao_ano, :proposicao_ementa, :proposicao_apresentacao, :proposicao_arquivada, :proposicao_norma)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':proposicao_id', $dados['proposicao_id'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_titulo', $dados['proposicao_titulo'], PDO::PARAM_STR);
            $stmt->bindParam(':proposicao_sigla', $dados['proposicao_sigla'], PDO::PARAM_STR);
            $stmt->bindParam(':proposicao_numero', $dados['proposicao_numero'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_ano', $dados['proposicao_ano'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_ementa', $dados['proposicao_ementa'], PDO::PARAM_STR);
            $stmt->bindParam(':proposicao_apresentacao', $dados['proposicao_apresentacao'], PDO::PARAM_STR);
            $stmt->bindParam(':proposicao_arquivada', $dados['proposicao_arquivada'], PDO::PARAM_BOOL);
            $stmt->bindParam(':proposicao_norma', $dados['proposicao_norma'], PDO::PARAM_BOOL);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('proposicoes_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function InserirProposicaoAutor($dados) {
        try {
            $query = "INSERT INTO proposicoes_autores (proposicao_id, proposicao_id_autor, proposicao_nome_autor, proposicao_partido_autor, proposicao_uf_autor, proposicao_assinatura, proposicao_proponente, proposicao_ano) VALUES (:proposicao_id, :proposicao_id_autor, :proposicao_nome_autor, :proposicao_partido_autor, :proposicao_uf_autor, :proposicao_assinatura, :proposicao_proponente, :proposicao_ano)";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':proposicao_id', $dados['proposicao_id'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_id_autor', $dados['proposicao_id_autor'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_nome_autor', $dados['proposicao_nome_autor'], PDO::PARAM_STR);
            $stmt->bindParam(':proposicao_partido_autor', $dados['proposicao_partido_autor'], PDO::PARAM_STR);
            $stmt->bindParam(':proposicao_uf_autor', $dados['proposicao_uf_autor'], PDO::PARAM_STR);
            $stmt->bindParam(':proposicao_assinatura', $dados['proposicao_assinatura'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_proponente', $dados['proposicao_proponente'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_ano', $dados['proposicao_ano'], PDO::PARAM_INT);

            $stmt->execute();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('proposicoes_autores_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function ListarProposicoesDeputado($ano, $tipo, $arquivada) {

        $depConfig = $this->config['deputado'];

        if ($arquivada == 1) {
            $query = "
                SELECT 
                    proposicoes.*, 
                    proposicoes_autores.proposicao_assinatura, 
                    proposicoes_autores.proposicao_proponente,
                    CASE 
                        WHEN proposicoes_autores.proposicao_assinatura = 1 
                             AND proposicoes_autores.proposicao_proponente = 1 
                        THEN true 
                        ELSE false 
                    END AS proposicao_autoria
                FROM 
                    proposicoes_autores 
                INNER JOIN 
                    proposicoes ON proposicoes_autores.proposicao_id = proposicoes.proposicao_id 
                WHERE 
                    proposicoes.proposicao_ano = :ano 
                    AND proposicoes.proposicao_sigla = :tipo 
                    AND proposicoes.proposicao_arquivada = 1 
                    AND proposicoes_autores.proposicao_id_autor = :id_deputado
                ORDER BY proposicoes.proposicao_titulo ASC
            ";
        } else {
            $query = "
                SELECT 
                    proposicoes.*, 
                    proposicoes_autores.proposicao_assinatura, 
                    proposicoes_autores.proposicao_proponente,
                    CASE 
                        WHEN proposicoes_autores.proposicao_assinatura = 1 
                             AND proposicoes_autores.proposicao_proponente = 1 
                        THEN true 
                        ELSE false 
                    END AS proposicao_autoria
                FROM 
                    proposicoes_autores 
                INNER JOIN 
                    proposicoes ON proposicoes_autores.proposicao_id = proposicoes.proposicao_id 
                WHERE 
                    proposicoes.proposicao_ano = :ano 
                    AND proposicoes.proposicao_sigla = :tipo 
                    AND proposicoes_autores.proposicao_id_autor = :id_deputado
                 ORDER BY proposicoes.proposicao_titulo ASC
            ";
        }

        try {
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':ano', $ano, PDO::PARAM_INT);
            $stmt->bindParam(':id_deputado', $depConfig['id_deputado'], PDO::PARAM_INT);
            $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);

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
            $this->logger->novoLog('proposicoes_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
        }
    }
}
