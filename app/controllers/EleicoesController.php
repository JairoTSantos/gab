<?php

require_once dirname(__DIR__) . '/models/EleicoesModel.php';

class EleicoesController
{

    private $eleicoesModel;
    private $arquivo;
    private $nomeProcurado;
    private $ano;


    public function __construct()
    {
        $this->ano = 2018;
        $this->eleicoesModel = new EleicoesModel();
        $config = require dirname(__DIR__) . '/config/config.php';
        $this->nomeProcurado = $config['deputado']['nome_completo'];
        $this->arquivo = dirname(__DIR__) . '/csv/votacao_' . $this->ano . '_municipio.csv'; // Use '/' para garantir portabilidade
    }



    public function inserirMunicipiosGeral()
    {
        $dados = $this->getCsv();
        foreach ($dados as $municipio) {
            $result = $this->eleicoesModel->inserirMunicipiosGeral($municipio);
        }
        return ['status' => 'success'];
    }


    public function getCsv()
    {
        $resultados = [];

        if (($handle = fopen($this->arquivo, 'r')) !== FALSE) {

            $header = fgetcsv($handle, 1000000, ";");

            while (($dados = fgetcsv($handle, 1000000, ";")) !== FALSE) {
                $dados = array_map(function ($campo) {
                    return mb_convert_encoding($campo, 'UTF-8', 'ISO-8859-1');
                }, $dados);

                if (isset($dados[20]) && trim($dados[20]) === $this->nomeProcurado) {
                    $municipio = $dados[14];
                    $votos = (int) $dados[21];
                    $cargo = $dados[18];

                    $encontrado = false;
                    foreach ($resultados as &$resultado) {
                        if ($resultado['municipio_nome'] === $municipio) {
                            $resultado['municipio_votos'] += $votos;
                            $encontrado = true;
                            break;
                        }
                    }

                    if (!$encontrado) {
                        $resultados[] = [
                            'municipio_nome' => $municipio,
                            'municipio_votos' => $votos,
                            'municipio_ano_eleicao' => $this->ano,
                            'municipio_cargo' => ucwords(strtolower($cargo))
                        ];
                    }
                }
            }

            fclose($handle);
            return $resultados;
        } else {
            throw new Exception("Erro ao abrir o arquivo: {$this->arquivo}.");
        }
    }

    public function buscarPorMunicipio($ano) {

        $result = $this->eleicoesModel->buscarPorMunicipio($ano);

        if ($result['status'] == 'success') {
            return $result;
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nada encontrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar resultados.'];
        }
    }




}
