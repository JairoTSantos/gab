<?php

require_once dirname(__DIR__) . '/models/ComissoesModel.php';
require_once dirname(__DIR__) . '/core/GetJson.php';


class ComissoesController {

    private $comissaoModel;

    public function __construct() {
        $this->comissaoModel = new ComissaoModel();
    }


    public function AtualizarComissoesDep() {

        $config = require dirname(__DIR__) . '/config/config.php';
        $depConfig = $config['deputado'];
        $dados = [];

        $jsonData = getJson('https://dadosabertos.camara.leg.br/api/v2/deputados/' . $depConfig['id_deputado'] . '/orgaos?ordem=ASC&ordenarPor=dataInicio&itens=100&dataInicio=' . ($depConfig['primeira_eleicao'] + 1) . '-01-01');

        foreach ($jsonData['dados'] as $comissao) {
            $dados[] = [
                'comissao_id' => $comissao['idOrgao'],
                'deputado_id' => $depConfig['id_deputado'],
                'comissao_entrada' => $comissao['dataInicio'],
                'comissao_saida' => $comissao['dataFim'],
                'comissao_cargo' => $comissao['titulo'],
                'comissao_cargo_id' => $comissao['codTitulo']
            ];
        }

        $result = $this->comissaoModel->AtualizarComissoesDeputado($dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Comissões inseridas com sucesso.'];
        } elseif ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao inserir as comissões.'];
        }

        return ['status' => 'error', 'message' => 'Erro desconhecido.'];
    }

    public function AtualizarComissoes() {
        $dados = [];

        $paginas = getJson('https://dadosabertos.camara.leg.br/api/v2/orgaos?itens=100&ordem=ASC&ordenarPor=id');

        if (!isset($paginas['links'])) {
            return ['status' => 'error', 'message' => 'Erro ao obter dados'];
        }

        $jsonData = [];

        $url_components = parse_url($paginas['links'][3]['href']);
        $query_params = [];
        parse_str($url_components['query'], $query_params);

        $pagina = isset($query_params['pagina']) ? $query_params['pagina'] : 1;

        for ($i = 1; $i <= $pagina; $i++) {
            $response = getJson('https://dadosabertos.camara.leg.br/api/v2/orgaos?pagina=' . $i . '&itens=100');

            if (isset($response['error'])) {
                return ['status' => 'error', 'message' => 'Erro ao obter dados da página ' . $i];
            }

            $jsonData = array_merge($jsonData, $response['dados']);
        }

        foreach ($jsonData as $comissao) {
            $dados[] = [
                'comissao_id' => basename($comissao['id']),
                'comissao_sigla' => $comissao['sigla'],
                'comissao_apelido' => $comissao['apelido'],
                'comissao_nome' => $comissao['nome'],
                'comissao_nome_publicacao' => $comissao['nomePublicacao'],
                'comissao_tipo' => $comissao['codTipoOrgao'],
                'comissao_descricao' => $comissao['tipoOrgao']
            ];
        }

        $result = $this->comissaoModel->AtualizarComissoes($dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Comissões inseridas com sucesso.'];
        } elseif ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao inserir as comissões.'];
        }

        return ['status' => 'error', 'message' => 'Erro desconhecido.'];
    }



    public function ListarComissoesDep($flag = false) {

        $result = $this->comissaoModel->ListarComissoesDep($flag);

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

    public function DetalhesComissao($comissao) {

        $result = $this->comissaoModel->DetalhesComissao($comissao);

        if ($result['status'] == 'success') {
            return $result;
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Comissão não encontrada'];
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
            return ['status' => 'empty', 'message' => 'Comissão não encontrada'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar comissões.'];
        }
    }
}
