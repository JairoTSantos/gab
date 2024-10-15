
<?php

require_once dirname(__DIR__) . '/models/EleicoesModel.php';

class EleicoesController {

    private $eleicoesModel;

    public function __construct() {
        $this->eleicoesModel = new EleicoesModel(); // Use a classe renomeada
    }

    public function getCargosDisputados() {

        $result = $this->eleicoesModel->getCargosDisputados();

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum cargo encontrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar cargos.'];
        }
    }


    public function getDetalhesEleicao($ano, $id_eleicao, $cargo) {

        $result = $this->eleicoesModel->getDetalhesEleicao($ano, $id_eleicao, $cargo);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum cargo encontrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar cargos.'];
        }
    }

    public function getResultadoEleicao($ano, $id_eleicao) {

        $result = $this->eleicoesModel->getResultadoEleicao($ano, $id_eleicao);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum cargo encontrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar cargos.'];
        }
    }
}
