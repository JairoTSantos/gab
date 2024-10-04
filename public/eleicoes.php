<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/app/controllers/EleicoesController.php';
$eleicoesController = new EleicoesController();

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

$ano = filter_input(INPUT_GET, 'ano', FILTER_SANITIZE_NUMBER_INT) ?: 2022;

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Eleições Gerais'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar() ?>
                <?php $layoutClass->cardDescription('<i class="fa-solid fa-user-plus"></i> Resultado das eleições', '<p class="card-text mb-2">Seção para conferência dos resultados das eleições.</p><p class="card-text mb-2">Aqui você poderá verificar os resultados de todas as eleições concorridas.</p><p class="card-text mb-0">Os dados apresentados são provenientes do Tribunal Superior Eleitoral (TSE) e estão sujeitos a revisão e conferência final.</p>') ?>

                <?php

                $resultado = $eleicoesController->getEleicoes();
                $tabela = [];

                foreach ($resultado as $eleicoes) {
                    $tabela[] = [
                        'Ano' => '<a href="info_eleicoes.php?ano=' . $eleicoes['ano'] . '">' . $eleicoes['ano'] . '</a>',
                        'Cargo' => ucwords($eleicoes['cargo']),
                        'Votos' => number_format($eleicoes['votos'], 0, ',', '.')
                    ];
                }

                echo $layoutClass->criarTabela($tabela);

                ?>


            </div>
        </div>
    </div>
</body>

</html>