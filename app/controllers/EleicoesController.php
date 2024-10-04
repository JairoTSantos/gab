<?php

class EleicoesController
{
    private $arquivo;
    private $nomeProcurado;

    public function __construct($ano)
    {
        $config = require dirname(__DIR__) . '/config/config.php';

        $this->nomeProcurado = 'ACÁCIO DA SILVA FAVACHO NETO';
        $this->arquivo = dirname(__DIR__) . '/csv/votacao_2022_municipio.csv'; // Use '/' para garantir portabilidade
    }

    public function PorMunicipio()
    {
        $resultados = [];
        $total_votos = 0;

        if (($handle = fopen($this->arquivo, 'r')) !== FALSE) {

            $header = fgetcsv($handle, 1000000, ";");

            while (($dados = fgetcsv($handle, 1000000, ";")) !== FALSE) {
                $dados = array_map(function ($campo) {
                    return mb_convert_encoding($campo, 'UTF-8', 'ISO-8859-1');
                }, $dados);


                if (isset($dados[20]) && trim($dados[20]) === $this->nomeProcurado) {
                    $municipio = $dados[14];
                    $id_municipio = $dados[13];
                    $votos = (int) $dados[21];

                    if (!isset($resultados[$municipio])) {
                        $resultados[$municipio] = [
                            'NM_MUNICIPIO' => $municipio,
                            'QT_VOTOS' => $votos,
                            'CD_MUNICIPIO' => $id_municipio
                        ];

                        $total_votos += $votos;
                    } else {
                        $resultados[$municipio]['QT_VOTOS'] += $votos;
                        $total_votos += $votos;
                    }
                }
            }
            
            fclose($handle);
            $resultados['total_votos'] = $total_votos;
            
            return $resultados;
        } else {
            throw new Exception("Erro ao abrir o arquivo: {$this->arquivo}.");
        }
    }
}
