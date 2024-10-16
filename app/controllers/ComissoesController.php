<?php

require_once dirname(__DIR__) . '/models/ComissoesModel.php';
require_once dirname(__DIR__) . '/core/GetJson.php';


class ComissoesController {

    private $comissaoModel;

    public function __construct() {
        $this->comissaoModel = new ComissaoModel();
    }

    public function atualizarComissoes() {

        $config = require dirname(__DIR__) . '/config/config.php';
        $depConfig = $config['deputado'];

        $dados = [];
        $jsonData = getJson('https://dadosabertos.camara.leg.br/api/v2/deputados/' . $depConfig['id_deputado'] . '/orgaos?ordem=ASC&ordenarPor=dataInicio&itens=100&dataInicio=' . ($depConfig['primeira_eleicao'] + 1) . '-01-01');
        if (!isset($jsonData['error'])) {
            foreach ($jsonData['dados'] as $comissao) {
                $dados[] = [
                    'comissao_id' => $comissao['idOrgao'],
                    'comissao_sigla' => $comissao['siglaOrgao'],
                    'comissao_apelido' => $comissao['nomePublicacao'],
                    'comissao_nome' => $comissao['nomeOrgao'],
                    'comissao_cargo' => $comissao['titulo'],
                    'comissao_inicio' => $comissao['dataInicio'],
                    'comissao_fim' => $comissao['dataFim']
                ];
            }

            $result = $this->comissaoModel->NovaComissao($dados);

            if ($result['status'] == 'success') {
                return ['status' => 'success', 'message' => 'Comissão inserida com sucesso.'];
            }

            if ($result['status'] == 'error') {
                return ['status' => 'error', 'message' => 'Erro ao inserir a comissão.'];
            }
        } else {
            return 'error';
        }
    }


    public function listarComissoes($flag = false) {

        $result = $this->comissaoModel->ListarComissoes($flag);

        if ($result['status'] == 'success') {
            return $result;
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhuma comissão registrada.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar comissões.'];
        }
    }

    public function ListarCargos($comissao) {

        $result = $this->comissaoModel->ListarCargos($comissao);

        if ($result['status'] == 'success') {
            return $result;
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum cargo registrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar comissões.'];
        }
    }
}
