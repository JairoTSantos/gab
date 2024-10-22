<?php


class EleicaoController {


    public function getDadosTse($ano) {
        // URL do arquivo ZIP
        $url = 'https://cdn.tse.jus.br/estatistica/sead/odsele/votacao_candidato_munzona/votacao_candidato_munzona_2022_AP.zip';

        // Extraindo o nome do arquivo a partir da URL
        $nomeArquivo = basename($url);

        // Diretório onde o arquivo será salvo
        $diretorio = '../app/data/downloads';

        // Verifica se o diretório existe, caso contrário cria
        if (!file_exists($diretorio)) {
            mkdir($diretorio, 0777, true);
        }

        // Caminho completo para salvar o arquivo
        $caminhoArquivo = $diretorio . '/' . $nomeArquivo;

        // Inicializando o cURL
        $ch = curl_init($url);

        // Abrindo o arquivo de destino para escrita
        $fp = fopen($caminhoArquivo, 'w+');

        // Configurando o cURL para gravar o conteúdo diretamente no arquivo
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecionamentos, se houver

        // Executando o download
        curl_exec($ch);

        // Fechando o cURL e o arquivo
        curl_close($ch);
        fclose($fp);

        return "Download concluído! O arquivo foi salvo como: " . $nomeArquivo;
    }
}
