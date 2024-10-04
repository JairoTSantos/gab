<?php



require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Logger.php';

class EleicoesModel
{

    private $db;
    private $logger;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }


    public function inserirMunicipiosGeral($dados)
    {
        try {

            $query = "INSERT INTO resultado_municipios (municipio_nome, municipio_votos, municipio_cargo, municipio_ano_eleicao) VALUES (:municipio_nome, :municipio_votos, :municipio_cargo, :municipio_ano_eleicao)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':municipio_nome', $dados['municipio_nome'], PDO::PARAM_STR);
            $stmt->bindParam(':municipio_votos', $dados['municipio_votos'], PDO::PARAM_INT);
            $stmt->bindParam(':municipio_ano_eleicao', $dados['municipio_ano_eleicao'], PDO::PARAM_INT);
            $stmt->bindParam(':municipio_cargo', $dados['municipio_cargo'], PDO::PARAM_STR);

            $stmt->execute();

            return ['status' => 'success'];

        } catch (PDOException $e) {
            $this->logger->novoLog('eleicoes_error', $e->getMessage());
            echo $e->getMessage();
            return ['status' => 'error'];
        }
    }

    public function buscarPorMunicipio($ano)
    {
        $query = "SELECT * FROM resultado_municipios WHERE municipio_ano_eleicao = :ano ORDER BY municipio_votos DESC";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':ano', $ano);
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
            return [
                'status' => 'error',
            ];
        }
    }



}
