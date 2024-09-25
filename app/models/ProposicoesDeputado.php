<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';
require_once dirname(__DIR__) . '/core/GetJson.php';

class ProposicoesDeputado {
    private $db;
    private $logger;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }

    public function atualizarProposicoes($dados) {

        try {

            $truncateQuery = 'TRUNCATE TABLE proposicoes_deputado';
            $this->db->exec($truncateQuery);

            $query = 'INSERT INTO proposicoes_deputado(proposicao_id, proposicao_ano, proposicao_numero, proposicao_sigla, proposicao_ementa, proposicao_apresentacao, proposicao_arquivada, proposicao_autoria_unica) VALUES (:proposicoes_deputado :proposicao_id, :proposicao_ano, :proposicao_numero, :proposicao_sigla, :proposicao_ementa, :proposicao_apresentacao, :proposicao_arquivada, :proposicao_autoria_unica)';
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':proposicao_id', $dados['proposicao_id'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_ano', $dados['proposicao_ano'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_numero', $dados['proposicao_numero'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_sigla', $dados['proposicao_sigla'], PDO::PARAM_STR);
            $stmt->bindParam(':proposicao_ementa', $dados['proposicao_ementa'], PDO::PARAM_STR);
            $stmt->bindParam(':proposicao_apresentacao', $dados['proposicao_apresentacao'], PDO::PARAM_STR);
            $stmt->bindParam(':proposicao_arquivada', $dados['proposicao_arquivada'], PDO::PARAM_BOOL);
            $stmt->bindParam(':proposicao_autoria_unica', $dados['proposicao_autoria_unica'], PDO::PARAM_BOOL);

            $stmt->execute();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('proposicoes_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }


    public function listarProposicoes($itens, $pagina, $ordem, $ordenarPor, $termo, $tipo){
        
    }
}
