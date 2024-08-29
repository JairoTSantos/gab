<?php

require dirname(__DIR__) . '/core/GetJson.php';

class ComissoesController {




    function BuscarComissaoDeputados($todas = true) {
        $config = require dirname(__DIR__) . '/config/config.php';

        if ($todas) {
            $url = 'https://dadosabertos.camara.leg.br/api/v2/deputados/' . $config['deputado']['id_deputado'] . '/orgaos?dataInicio=' . $config['deputado']['inicio_legislatura'] . '&dataFim=' . $config['deputado']['fim_legislatura'] . '&ordem=DESC&ordenarPor=dataInicio';
        } else {
            $url = 'https://dadosabertos.camara.leg.br/api/v2/deputados/' . $config['deputado']['id_deputado'] . '/orgaos?ordem=DESC&ordenarPor=dataInicio';
        }

        $dados = getJson($url);

        if (empty($dados['dados'])) {
            return [];
        }

        $comissoesAgrupadas = array_keys($this->AgruparDadosPorIndice($dados['dados'], 'idOrgao'));
        $comissoes = [];

        foreach ($dados['dados'] as $comissao) {
            if (in_array($comissao['idOrgao'], $comissoesAgrupadas)) {
                if ($comissao['siglaOrgao'] !== 'PLEN' && $comissao['siglaOrgao'] !== 'PLENARIO') {
                    if (!isset($comissoes[$comissao['idOrgao']])) {
                        $comissoes[$comissao['idOrgao']] = [
                            'comissao_id' => $comissao['idOrgao'],
                            'comissao_nome' => $comissao['nomeOrgao'],
                            'comissao_sigla' => $comissao['siglaOrgao'],
                            'comissao_apelido' => $comissao['nomePublicacao'],
                            'comissao_cargos' => []
                        ];
                    }

                    $comissoes[$comissao['idOrgao']]['comissao_cargos'][] = [
                        'cargo_titulo' => $comissao['titulo'],
                        'cargo_data_inicio' => date('Y-m-d', strtotime($comissao['dataInicio'])),
                        'cargo_data_fim' => ($comissao['dataFim'] ? date('Y-m-d', strtotime($comissao['dataFim'])) : null)
                    ];
                }
            }
        }

        return $comissoes;
    }


    function BuscarReunioes($todas, $data, $tipo = 'Reunião Deliberativa') {

        $comissoesDeputados = $this->BuscarComissaoDeputados(false);
        $dadosReunioes = [];
        $reunioes = [];

        if ($todas) {
            $dadosReunioes = getJson('https://dadosabertos.camara.leg.br/api/v2/eventos?dataInicio=' . $data . '&dataFim=' . $data . '&itens=100&ordem=ASC&ordenarPor=dataHoraInicio')['dados'];
        } else {
            foreach ($comissoesDeputados as $comissao) {
                $reunioesComissao = getJson('https://dadosabertos.camara.leg.br/api/v2/eventos?idOrgao=' . $comissao['comissao_id'] . '&dataInicio=' . $data . '&dataFim=' . $data . '&itens=100&ordem=ASC&ordenarPor=dataHoraInicio')['dados'];
                $dadosReunioes = array_merge($dadosReunioes, $reunioesComissao);
            }
        }

        if (empty($dadosReunioes)) {
            return [];
        }

        foreach ($dadosReunioes as $reuniao) {
            if ($reuniao['descricaoTipo'] === $tipo) {
                $reunioes[] = [
                    'reuniao_id' => $reuniao['id'],
                    'reuniao_situacao' => $reuniao['situacao'],
                    'reuniao_tipo' => $reuniao['descricaoTipo'],
                    'reuniao_descricao' => $reuniao['descricao'],
                    'reuniao_inicio' => $reuniao['dataHoraInicio'],
                    'reuniao_fim' => $reuniao['dataHoraFim'],
                    'reuniao_video' => $reuniao['urlRegistro'],
                    'reuniao_orgao' => $reuniao['orgaos'],
                    'reuniao_local' => $reuniao['localCamara']
                ];
            }
        }
        return $this->AgruparDadosPorIndice($reunioes, 'reuniao_inicio');
    }


    function AgruparDadosPorIndice(array $dados, string $indice): array {
        $dadosAgrupados = [];

        foreach ($dados as $item) {
            if (isset($item[$indice])) {
                $valorIndice = $item[$indice];

                if (!isset($dadosAgrupados[$valorIndice])) {
                    $dadosAgrupados[$valorIndice] = [];
                }

                $dadosAgrupados[$valorIndice][] = $item;
            }
        }

        ksort($dadosAgrupados);

        return $dadosAgrupados;
    }


    function BuscarPauta($id_reuniao) {
        $config = require dirname(__DIR__) . '/config/config.php';

        $dadosPauta = getJson('https://dadosabertos.camara.leg.br/api/v2/eventos/' . $id_reuniao . '/pauta');

        if (empty($dadosPauta['dados'])) {
            return [];
        }

        foreach ($dadosPauta['dados'] as $proposicao) {
            $pauta[] = array_merge(
                [
                    'pauta_ordem' => $proposicao['ordem'],
                    'pauta_regime' => $proposicao['regime'],
                    'pauta_regime_cod' => $proposicao['codRegime'],
                    'pauta_titulo' => $proposicao['titulo'],
                    'pauta_situacao' => $proposicao['situacaoItem'],
                    'texto_parecer' => $proposicao['textoParecer'],
                ],
                !empty($proposicao['proposicao_']) ? [
                    'proposicao_em_votacao' => [
                        'proposicao_id' => $proposicao['proposicao_']['id'],
                        'proposicao_titulo' => $proposicao['proposicao_']['siglaTipo'] . ' ' . $proposicao['proposicao_']['numero'] . '/' . $proposicao['proposicao_']['ano'],
                        'proposicao_ementa' => $proposicao['proposicao_']['ementa'],
                    ]
                ] : [],
                !empty($proposicao['relator']) ? [
                    'proposicao_relator' => [
                        'relator_id' => $proposicao['relator']['id'],
                        'relator_nome' => $proposicao['relator']['nome'],
                        'relator_flag' => $proposicao['relator']['id'] === $config['deputado']['id_deputado'] ? true:false,
                        'relator_partido' => $proposicao['relator']['siglaPartido'],
                    ]
                ] : [],
                !empty($proposicao['proposicaoRelacionada_']) ? [
                    'proposicao_relacionada' => [
                        'proposicao_id' => $proposicao['proposicaoRelacionada_']['id'],
                        'proposicao_titulo' => $proposicao['proposicaoRelacionada_']['siglaTipo'] . ' ' . $proposicao['proposicaoRelacionada_']['numero'] . '/' . $proposicao['proposicaoRelacionada_']['ano'],
                        'proposicao_ementa' => $proposicao['proposicaoRelacionada_']['ementa'],
                        //'proposicao_autores' => $this->BuscarAutores($proposicao['proposicaoRelacionada_']['id'])
                    ]
                ] : []
            );
        }

        return $pauta;
    }


    public function BuscarAutores($id) {
        $dadosAutores = getJson('https://dadosabertos.camara.leg.br/api/v2/proposicoes/' . $id . '/autores');

        foreach ($dadosAutores['dados'] as $autor) {
            $autores[] = [
                'autor_id' => $autor['uri'],
                'autor_nome' => $autor['nome'],
                'autor_proponente' => $autor['proponente'],
                'autor_assinatura' => $autor['ordemAssinatura']
            ];
        }

        return $autores;
    }



    
}



