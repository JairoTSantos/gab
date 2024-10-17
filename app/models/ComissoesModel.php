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

    public function AtualizarComissoes($dados) {
        try {

            $queryApagar = ('TRUNCATE TABLE comissoes');
            $stmtApagar = $this->db->prepare($queryApagar);
            $stmtApagar->execute();

            $query = "INSERT INTO comissoes (comissao_id, comissao_sigla, comissao_apelido, comissao_nome,  comissao_nome_publicacao, comissao_tipo, comissao_descricao) VALUES (:comissao_id, :comissao_sigla, :comissao_apelido,  :comissao_nome, :comissao_nome_publicacao, :comissao_tipo, :comissao_descricao)";
            $stmt = $this->db->prepare($query);

            foreach ($dados as $comissao) {
                $stmt->bindParam(':comissao_id', $comissao['comissao_id'], PDO::PARAM_INT);
                $stmt->bindParam(':comissao_sigla', $comissao['comissao_sigla'], PDO::PARAM_STR);
                $stmt->bindParam(':comissao_apelido', $comissao['comissao_apelido'], PDO::PARAM_STR);
                $stmt->bindParam(':comissao_nome', $comissao['comissao_nome'], PDO::PARAM_STR);
                $stmt->bindParam(':comissao_nome_publicacao', $comissao['comissao_nome_publicacao'], PDO::PARAM_STR);
                $stmt->bindParam(':comissao_tipo', $comissao['comissao_tipo'], PDO::PARAM_INT);

                $stmt->bindParam(':comissao_descricao', $comissao['comissao_descricao'], PDO::PARAM_STR);

                $stmt->execute();
            }

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('comissao_error', $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }


    public function AtualizarComissoesDeputado($dados) {
        try {

            $queryApagar = ('TRUNCATE TABLE comissoes_dep');
            $stmtApagar = $this->db->prepare($queryApagar);
            $stmtApagar->execute();

            $query = "INSERT INTO comissoes_dep (comissao_id, deputado_id, comissao_entrada, comissao_saida, comissao_cargo, comissao_cargo_id) VALUES (:comissao_id, :deputado_id, :comissao_entrada, :comissao_saida, :comissao_cargo, :comissao_cargo_id)";
            $stmt = $this->db->prepare($query);

            foreach ($dados as $comissao) {
                $stmt->bindParam(':comissao_id', $comissao['comissao_id'], PDO::PARAM_INT);
                $stmt->bindParam(':deputado_id', $comissao['deputado_id'], PDO::PARAM_INT);
                $stmt->bindParam(':comissao_entrada', $comissao['comissao_entrada'], PDO::PARAM_STR);
                $stmt->bindParam(':comissao_saida', $comissao['comissao_saida'], PDO::PARAM_STR);
                $stmt->bindParam(':comissao_cargo', $comissao['comissao_cargo'], PDO::PARAM_STR);
                $stmt->bindParam(':comissao_cargo_id', $comissao['comissao_cargo_id'], PDO::PARAM_INT);
                $stmt->execute();
            }

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->logger->novoLog('comissao_error', $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }


    public function ListarComissoesDep($flag) {

        if ($flag) {
            $query = "SELECT comissoes.comissao_id, comissoes.comissao_sigla, comissoes.comissao_apelido, comissoes.comissao_nome, comissoes.comissao_nome_publicacao, comissoes.comissao_tipo, comissoes.comissao_descricao FROM comissoes_dep INNER JOIN comissoes ON comissoes_dep.comissao_id = comissoes.comissao_id WHERE comissoes_dep.comissao_saida IS NOT NULL GROUP BY comissoes_dep.comissao_id;";
        } else {
            $query = "SELECT comissoes.comissao_id, comissoes.comissao_sigla, comissoes.comissao_apelido, comissoes.comissao_nome, comissoes.comissao_nome_publicacao, comissoes.comissao_tipo, comissoes.comissao_descricao FROM comissoes_dep INNER JOIN comissoes ON comissoes_dep.comissao_id = comissoes.comissao_id WHERE comissoes_dep.comissao_saida IS NULL GROUP BY comissoes_dep.comissao_id;";
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

        $query = "SELECT * FROM comissoes_dep WHERE comissao_id = ".$comissao." ORDER BY comissao_entrada DESC;";

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

    public function DetalhesComissao($comissao) {

        $query = "SELECT comissoes.*, CONCAT('https://camara.leg.br/', comissoes.comissao_sigla) as comissao_site FROM comissoes WHERE comissoes.comissao_id = " . $comissao." ORDER BY comissoes.comissao_id ASC";

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
