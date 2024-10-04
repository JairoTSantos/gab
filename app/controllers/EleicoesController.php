<?php



class EleicoesController
{

    //CRIAR UM MEIO DE BUSCAR PELO BIGQUERY
    public function getEleicoes()
    {
        $caminhoArquivo = dirname(__DIR__) . '/json/resultado_eleicoes.json';

        if (!file_exists($caminhoArquivo)) {
            return []; // Retorna um array vazio se o arquivo não existir
        }

        $conteudo = file_get_contents($caminhoArquivo);
        $dados = json_decode($conteudo, true); // Decodifica o JSON em um array

        if (json_last_error() !== JSON_ERROR_NONE) {
            return []; // Retorna um array vazio se houver erro na decodificação
        }

        // Ordena os dados pelo campo 'ano' em ordem decrescente
        usort($dados, function ($a, $b) {
            return (isset($b['ano']) && isset($a['ano'])) ? $b['ano'] <=> $a['ano'] : 0; // Ordena por 'ano'
        });

        return $dados; // Retorna os dados ordenados
    }


    public function getEleicoesMunicipios($ano)
    {
        $caminhoArquivoJson = dirname(__DIR__) . '/json/resultado_eleicoes_municipio.json';
        $caminhoArquivoCsv = dirname(__DIR__) . '/json/br_bd_diretorios_brasil_municipio.csv';

        // Verifica se o arquivo JSON existe
        if (!file_exists($caminhoArquivoJson)) {
            return []; // Retorna um array vazio se o arquivo não existir
        }

        // Lê e decodifica o arquivo JSON
        $conteudoJson = file_get_contents($caminhoArquivoJson);
        $dadosJson = json_decode($conteudoJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return []; // Retorna um array vazio se houver erro na decodificação do JSON
        }

        // Cria um array associativo do arquivo CSV
        $municipios = [];
        if (($handle = fopen($caminhoArquivoCsv, 'r')) !== FALSE) {
            // Lê o cabeçalho
            $cabecalho = fgetcsv($handle, 1000, ',');

            // Lê cada linha do CSV
            while (($linha = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $municipio = array_combine($cabecalho, $linha); // Combina cabeçalho com dados da linha
                $municipios[$municipio['id_municipio']] = $municipio['nome']; // Mapeia id_municipio para nome
            }
            fclose($handle);
        }

        // Filtra os dados do JSON com base no ano
        $resultadosFiltrados = array_filter($dadosJson, function ($item) use ($ano) {
            return isset($item['ano']) && $item['ano'] == $ano; // Verifica se 'ano' existe e é igual ao ano desejado
        });

        // Adiciona o nome do município ao array de resultados filtrados
        foreach ($resultadosFiltrados as &$resultado) {
            if (isset($resultado['id_municipio']) && isset($municipios[$resultado['id_municipio']])) {
                $resultado['nome_municipio'] = $municipios[$resultado['id_municipio']]; // Adiciona o nome ao resultado
            }
        }

        // Ordena os resultados filtrados por votos em ordem decrescente
        usort($resultadosFiltrados, function ($a, $b) {
            // Verifica se o campo de votos existe e é numérico
            return (isset($b['votos']) && isset($a['votos'])) ? $b['votos'] <=> $a['votos'] : 0;
        });

        return $resultadosFiltrados; // Retorna os dados filtrados e ordenados
    }







}
