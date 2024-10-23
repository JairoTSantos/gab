<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';
require_once dirname(__DIR__) . '/app/core/GetJson.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

$config = require dirname(__DIR__) . '/app/config/config.php';
$depConfig = $config['deputado'];

require_once dirname(__DIR__) . '/app/controllers/NotaTecnicaController.php';
$notaTecnicaController = new NotaTecnicaController();

$proposicaoIdGet = $_GET['proposicao'];
$imprimir = $_GET['imprimir'] ?: 0;

$proposicao = $notaTecnicaController->BuscarNotaTecnica('nota_proposicao', $proposicaoIdGet);

$dadosJson = getJson('https://dadosabertos.camara.leg.br/api/v2/proposicoes/' . $proposicaoIdGet)['dados'];

if (empty($dadosJson)) {
    echo "<script>alert('Não existe nota para imprimir');window.close();</script>";
    exit();
}

if (!isset($proposicao['status']) || $proposicao['status'] != 'success') {
    echo "<script>alert('Não existe nota para imprimir');window.close();</script>";
    exit();
}

?>



<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Nota Técnica'); ?>

    <style>
        @media print {

            /* Estilo para impressão */
            body {
                background-color: white;
                margin: 0;
                padding: 0;
                font-size: 12pt;
                /* Ajuste a fonte se necessário */
            }

            /* Forçar quebra de página antes de cada nova nota técnica, se necessário */
            .page-break {
                page-break-before: always;
            }

            /* Para evitar quebras de página em elementos que não devem ser quebrados */
            .no-break {
                page-break-inside: avoid;
            }
        }
    </style>
    <?php

    if ($imprimir == 1) {
        echo ' <script>
        window.print();

        // Adiciona um evento que fecha a janela após a impressão
        window.onafterprint = function() {
            window.close();
        };
    </script>';
    }


    ?>

</head>

<body style="background-image: none; background-color:white">

    <div class="container-fluid p-2">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2 border-0 no-break">
                    <div class="card-body p-2">
                        <img src="./img/brasaooficialcolorido.png" style="width: 150px;" class="card-img-top mx-auto d-block" alt="...">
                        <p class="card-text mb-0 text-center" style="font-size: 1.1em;">Câmara dos Deputados</p>
                        <p class="card-text mb-4 text-center" style="font-size: 1em;">Gabinete do Deputado <?php echo $depConfig['nome_deputado'].' - '.$depConfig['partido_deputado'].'/'.$depConfig['estado_deputado'] ?></p>

                        <p class="card-text mb-2 mt-4 text-center" style="font-size: 1.4em;"><b><?php echo $dadosJson['siglaTipo'] . ' ' . $dadosJson['numero'] . '/' . $dadosJson['ano'] ?></b> </p>
                        <p class="card-text mb-2 text-center" style="font-size: 1.2em;"><b><?php echo $proposicao['dados']['nota_titulo'] ?></b></p>
                        <p class="card-text mb-4 text-center style=" font-size: 1.2em;">(<?php echo $proposicao['dados']['nota_resumo'] ?>)</p>
                        <p class="card-text mb-0 text-center" style="font-size: 1.3em;"><b>Nota técnica</b></p>
                        <p class="card-text mb-4 text-center" style="font-size: 0.8em;">criada por (<?php echo $proposicao['dados']['usuario_nome'] ?>)</p>
                        <p class="card-text"><?php echo $proposicao['dados']['nota_texto'] ?></p>
                    </div>
                </div>
            </div>
        </div>

</body>

</html>