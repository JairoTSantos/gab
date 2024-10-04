<?php

$arquivo = dirname(__DIR__) . '\app\csv\votacao_2022_municipio.csv';

$nomeProcurado = 'ACÁCIO DA SILVA FAVACHO NETO';

$resultados = []; // Array para armazenar as linhas encontradas

if (($handle = fopen($arquivo, 'r')) !== FALSE) {
    // Configura o limite de memória, se necessário
    ini_set('memory_limit', '512M');

    // Lê o cabeçalho (opcional, se houver)
    $header = fgetcsv($handle, 10000, ";");

    // Loop através de cada linha do arquivo CSV
    while (($dados = fgetcsv($handle, 1000, ";")) !== FALSE) {
        // Converte cada campo de Latin-1 para UTF-8
        $dados = array_map(function ($campo) {
            return mb_convert_encoding($campo, 'UTF-8', 'ISO-8859-1');
        }, $dados);

        // Verifica se o nome procurado está na coluna 'NM_VOTAVEL'
        if (isset($dados[20]) && trim($dados[20]) === $nomeProcurado) {
            // Armazena o nome do município e a quantidade de votos
            $municipio = $dados[14];
            $votos = (int) $dados[21];

            // Se o município já existir no array, soma os votos
            if (isset($resultados[$municipio])) {
                $resultados[$municipio]['QT_VOTOS'] += $votos;
            } else {
                // Caso contrário, cria uma nova entrada no array
                $resultados[$municipio] = [
                    'NM_MUNICIPIO' => $municipio,
                    'NM_VOTAVEL' => $dados[20],
                    'QT_VOTOS' => $votos
                ];
            }
        }
    }

    fclose($handle);

    // Ordena o array por NM_MUNICIPIO
    usort($resultados, function ($a, $b) {
        return strcmp($a['NM_MUNICIPIO'], $b['NM_MUNICIPIO']);
    });

    // Exibe os resultados agrupados e ordenados
    echo json_encode($resultados);

} else {
    echo "Erro ao abrir o arquivo.";
}

