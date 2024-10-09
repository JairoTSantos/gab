<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Home'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>


            <?php
            // Coloque o nome do arquivo que você quer abrir aqui.
            $arquivo = '../app/data/2022/votacao_candidato_munzona_2022_AP.csv';

            // Inicializa um array para armazenar os votos
            $votos_acacio = [];
            $soma_total_votos = 0;

            // Abre o arquivo CSV
            if (($handle = fopen($arquivo, 'r')) !== FALSE) {
                // Lê o cabeçalho do CSV
                $cabecalho = fgetcsv($handle, 1000, ';');

                // Encontra os índices das colunas relevantes
                $indice_urna_candidato = array_search('NM_URNA_CANDIDATO', $cabecalho);
                $indice_municipio = array_search('NM_MUNICIPIO', $cabecalho);
                $indice_sit_turno = array_search('DS_SIT_TOT_TURNO', $cabecalho);
                $indice_votos = array_search('QT_VOTOS_NOMINAIS', $cabecalho);

                // Lê cada linha do CSV
                while (($linha = fgetcsv($handle, 1000, ';')) !== FALSE) {
                    // Verifica se a linha corresponde ao candidato Acácio Favacho
                    if ($linha[$indice_urna_candidato] === 'ACÁCIO FAVACHO') {
                        $municipio = $linha[$indice_municipio];
                        $situacao = $linha[$indice_sit_turno];
                        $votos = (int)$linha[$indice_votos];

                        // Armazena os votos por município
                        if (!isset($votos_acacio[$municipio][$situacao])) {
                            $votos_acacio[$municipio][$situacao] = 0;
                        }
                        $votos_acacio[$municipio][$situacao] += $votos;

                        // Soma total de votos
                        $soma_total_votos += $votos;
                    }
                }
                fclose($handle);
            }

            // Exibe o resultado
            echo "Resultado dos Votos:\n";
            foreach ($votos_acacio as $municipio => $situacoes) {
                foreach ($situacoes as $situacao => $total_votos) {
                    echo "Município: $municipio, Situação: $situacao, Total de Votos: $total_votos\n";
                }
            }
            echo "Soma total de votos do Acácio: $soma_total_votos\n";
            ?>




        </div>
    </div>
    </div>
    </div>
</body>

</html>