<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class ComissaoModel {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }

    public function NovaComissao($dados) {
        try {

            $queryApagar = ('TRUNCATE TABLE comissoes');
            $stmtApagar = $this->db->prepare($queryApagar);
            $stmtApagar->execute();

            $query = "INSERT INTO comissoes (comissao_id, comissao_sigla, comissao_apelido, comissao_nome, comissao_cargo, comissao_inicio, comissao_fim) VALUES (:comissao_id, :comissao_sigla, :comissao_apelido, :comissao_nome, :comissao_cargo, :comissao_inicio, :comissao_fim)";
            $stmt = $this->db->prepare($query);

            foreach ($dados as $comissao) {
                $stmt->bindParam(':comissao_id', $comissao['comissao_id'], PDO::PARAM_INT);
                $stmt->bindParam(':comissao_sigla', $comissao['comissao_sigla'], PDO::PARAM_STR);
                $stmt->bindParam(':comissao_apelido', $comissao['comissao_apelido'], PDO::PARAM_STR);
                $stmt->bindParam(':comissao_nome', $comissao['comissao_nome'], PDO::PARAM_STR);
                $stmt->bindParam(':comissao_cargo', $comissao['comissao_cargo'], PDO::PARAM_STR);
                $stmt->bindParam(':comissao_inicio', $comissao['comissao_inicio'], PDO::PARAM_STR);
                $stmt->bindParam(':comissao_fim', $comissao['comissao_fim'], PDO::PARAM_STR);

                $stmt->execute();
            }

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('comissao_error', $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }




    public function ListarComissoes($flag) {

        if ($flag) {
            $query = "SELECT comissao_id, comissao_nome, comissao_apelido, comissao_sigla, COUNT(*) AS total_comissoes FROM comissoes GROUP BY comissao_id, comissao_nome, comissao_apelido, comissao_sigla ORDER BY comissao_sigla ASC";
        } else {
            $query = "SELECT comissao_id, comissao_nome, comissao_apelido, comissao_sigla, COUNT(*) AS total_comissoes FROM comissoes WHERE comissao_fim IS NULL GROUP BY comissao_id, comissao_nome, comissao_apelido, comissao_sigla ORDER BY comissao_sigla ASC";
        }

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
            $this->logger->novoLog('comissao_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
        }
    }

    public function ListarCargos($comissao) {

        $query = "SELECT comissao_cargo, comissao_inicio, comissao_fim FROM comissoes WHERE comissao_id = " . $comissao . " ORDER BY comissao_inicio DESC";

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
            $this->logger->novoLog('comissao_error', $e->getMessage());
            return [
                'status' => 'error',
            ];
        }
    }
}
