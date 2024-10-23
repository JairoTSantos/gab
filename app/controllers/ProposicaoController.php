<?php

require_once dirname(__DIR__) . '/models/ProposicaoModel.php';
require_once dirname(__DIR__) . '/core/GetJson.php';


class ProposicaoController {

    private $proposicaoModel;

    public function __construct() {
        $this->proposicaoModel = new ProposicaoModel();
    }


    public function atualizaProposicoes($ano) {
        $dadosJson = getJson('https://dadosabertos.camara.leg.br/arquivos/proposicoes/json/proposicoes-' . $ano . '.json');

        $sucesso = 0;
        $erro = 0;

        $this->proposicaoModel->limparBanco('proposicoes', $ano);

        foreach ($dadosJson['dados'] as $proposicao) {
            $dadosProposicao = [
                'proposicao_id' => $proposicao['id'],
                'proposicao_titulo' => $proposicao['siglaTipo'] . ' ' . $proposicao['numero'] . '/' . $proposicao['ano'],
                'proposicao_sigla' => $proposicao['siglaTipo'],
                'proposicao_numero' => $proposicao['numero'],
                'proposicao_ano' => $proposicao['ano'] == 0 ? $ano : $proposicao['ano'],
                'proposicao_ementa' => $proposicao['ementa'],
                'proposicao_apresentacao' => $proposicao['dataApresentacao'],
                'proposicao_arquivada' => $proposicao['ultimoStatus']['descricaoSituacao'] == 'Arquivada' ? true : false,
                'proposicao_norma' => $proposicao['ultimoStatus']['descricaoSituacao'] == 'Transformado em Norma Jurídica' ? true : false
            ];

            $result = $this->proposicaoModel->InserirProposicao($dadosProposicao);

            if ($result['status'] == 'success') {
                $sucesso++;
            } else {
                $erro++;
            }
        }

        if ($erro == 0) {
            return ['status' => 'success', 'message' => "$sucesso proposições inseridas com sucesso."];
        } else {
            return ['status' => 'partial_success', 'message' => "$sucesso proposições inseridas com sucesso, $erro erros ocorreram."];
        }
    }

    public function atualizaAutoresProposicoes($ano) {
        $dadosJson = getJson('https://dadosabertos.camara.leg.br/arquivos/proposicoesAutores/json/proposicoesAutores-' . $ano . '.json');

        $sucesso = 0;
        $erro = 0;

        $this->proposicaoModel->limparBanco('proposicoes_autores', $ano);

        foreach ($dadosJson['dados'] as $proposicao) {
            $dadosProposicao = [
                'proposicao_id' => $proposicao['idProposicao'],
                'proposicao_id_autor' => isset($proposicao['idDeputadoAutor']) ? $proposicao['idDeputadoAutor'] : 0,
                'proposicao_nome_autor' => $proposicao['nomeAutor'],
                'proposicao_partido_autor' => $proposicao['siglaPartidoAutor'],
                'proposicao_uf_autor' => isset($proposicao['siglaUFAutor']) ? $proposicao['siglaUFAutor'] : null,
                'proposicao_assinatura' => isset($proposicao['ordemAssinatura']) ? $proposicao['ordemAssinatura'] : null,
                'proposicao_proponente' => $proposicao['proponente'],
                'proposicao_ano' => $ano
            ];

            $result = $this->proposicaoModel->InserirProposicaoAutor($dadosProposicao);

            if ($result['status'] == 'success') {
                $sucesso++;
            } else {
                $erro++;
            }
        }

        if ($erro == 0) {
            return ['status' => 'success', 'message' => "$sucesso autores inseridas com sucesso."];
        } else {
            return ['status' => 'partial_success', 'message' => "$sucesso autores inseridas com sucesso"];
        }
    }


    public function ListarProposicoesDeputado($ano, $tipo, $arquivada) {
        
        $result = $this->proposicaoModel->ListarProposicoesDeputado($ano, $tipo, $arquivada);

        if ($result['status'] == 'success') {
            return $result;
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhuma proposição encontrada.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar proposições.'];
        }
    }
}
