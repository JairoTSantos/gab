
<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class EleicoesModel { // Renomeie para EleicoesModel

    private $db;
    private $logger;
    private $primeira_eleicao;
    private $ultima_eleicao;
    private $nome_dep;
    private $estado_dep;

    public function __construct() {
        $config = require dirname(__DIR__) . '/config/config.php';
        $depConfig = $config['deputado'];

        $this->primeira_eleicao = $depConfig['primeira_eleicao'];
        $this->ultima_eleicao = $depConfig['ultima_eleicao'];
        $this->nome_dep = $depConfig['nome_deputado'];
        $this->estado_dep = $depConfig['estado_deputado'];

        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }


    public function getCargosDisputados() {
        try {
            $todosDados = []; // Array para armazenar todos os resultados

            // Loop pelos anos, pulando de 2 em 2
            for ($ano = $this->primeira_eleicao; $ano < $this->ultima_eleicao + 2; $ano += 2) {
                // Monta a query com o ano atual
                $query = "SELECT SUM(QT_VOTOS_NOMINAIS) AS votos_validos, DS_CARGO, CD_CARGO, ANO_ELEICAO, CD_ELEICAO, DS_SIT_TOT_TURNO FROM votacao_candidato_munzona_" . $ano . "_" . $this->estado_dep . " WHERE NM_URNA_CANDIDATO = '" . $this->nome_dep . "' GROUP BY DS_CARGO, CD_CARGO, ANO_ELEICAO, CD_ELEICAO, DS_SIT_TOT_TURNO";
                $stmt = $this->db->prepare($query);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Armazena os resultados no array
                if (!empty($result)) {
                    $todosDados = array_merge($todosDados, $result); // Adiciona os resultados ao array principal
                }
            }

            // Verifica se o array está vazio
            if (empty($todosDados)) {
                return ['status' => 'empty'];
            }

            return [
                'status' => 'success',
                'dados' => $todosDados // Retorna todos os dados acumulados
            ];
        } catch (PDOException $e) {
            $this->logger->novoLog('eleicoes_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }


    public function getDetalhesEleicao($ano, $id_eleicao, $cargo) {

        try {

            if ($ano <= 2016) {
                $query = "SELECT (SUM(QT_VOTOS_NOMINAIS) + SUM(QT_VOTOS_LEGENDA)) AS total_votos_validos, SUM(QT_VOTOS_BRANCOS) AS total_votos_brancos, SUM(QT_VOTOS_NULOS) AS total_votos_nulos, DS_CARGO FROM detalhe_votacao_munzona_" . $ano . "_" . $this->estado_dep . " WHERE CD_CARGO = " . $cargo . " AND CD_ELEICAO = " . $id_eleicao . " GROUP BY DS_CARGO";
            } else {
                $query = "SELECT (SUM(QT_VOTOS_NOMINAIS_VALIDOS) + (SUM(QT_TOTAL_VOTOS_LEG_VALIDOS))) AS total_votos_validos, SUM(QT_VOTOS_BRANCOS) AS total_votos_brancos, SUM(QT_VOTOS_NULOS) AS total_votos_nulos, DS_CARGO FROM detalhe_votacao_munzona_" . $ano . "_" . $this->estado_dep . " WHERE CD_CARGO = " . $cargo . " AND CD_ELEICAO = " . $id_eleicao . " GROUP BY DS_CARGO";
            }


            $stmt = $this->db->prepare($query);
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
            $this->logger->novoLog('eleicoes_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }

    public function getResultadoEleicao($ano, $id_eleicao) {


        try {

            if($ano < 2016){
                $query = "SELECT SUM(QT_VOTOS_NOMINAIS) AS total_votos, NM_MUNICIPIO FROM votacao_candidato_munzona_" . $ano . "_" . $this->estado_dep . " WHERE NM_URNA_CANDIDATO = '" . $this->nome_dep . "' AND CD_ELEICAO = ".$id_eleicao." GROUP BY NM_MUNICIPIO ORDER BY total_votos DESC;";
            }else{
                $query = "SELECT SUM(QT_VOTOS_NOMINAIS) AS total_votos, NM_MUNICIPIO FROM votacao_candidato_munzona_" . $ano . "_" . $this->estado_dep . " WHERE NM_URNA_CANDIDATO = '" . $this->nome_dep . "' AND CD_ELEICAO = ".$id_eleicao." GROUP BY NM_MUNICIPIO ORDER BY total_votos DESC;";
            }

            
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
            $this->logger->novoLog('eleicoes_error', $e->getMessage());
            return ['status' => 'error'];
        }
    }
}
