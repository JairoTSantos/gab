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
                <?php $layoutClass->navBar(true, 'eleicoes.php') ?>
                <?php $layoutClass->cardDescription('<i class="fa-solid fa-user-plus"></i> Resultado das eleições', '<p class="card-text mb-2">Seção para conferência dos resultados das eleições.</p><p class="card-text mb-2">Aqui você poderá verificar os resultados de todas as eleições concorridas.</p><p class="card-text mb-0">Os dados apresentados são provenientes do Tribunal Superior Eleitoral (TSE) e estão sujeitos a revisão e conferência final.</p>') ?>

                <?php

                $resultado = $eleicoesController->getEleicoesMunicipios($ano);
                $tabela = [];
                $totalVotos = 0;


                foreach ($resultado as $eleicao) {
                    $totalVotos += $eleicao['votos'];
                }

                foreach ($resultado as $eleicao) {

                    $porcentagem = $totalVotos > 0 ? ($eleicao['votos'] / $totalVotos) * 100 : 0;

                    $porcentagemFormatada = number_format($porcentagem, 2);

                    $barraProgresso = '<div class="progress" style="width: 100%; background-color: #f3f3f3;">
                           <div class="progress-bar" style="width: ' . $porcentagemFormatada . '%; background-color: #4caf50;"></div>
                       </div>';
                    $tabela[] = [
                        'Municipio' => ucwords($eleicao['nome_municipio']),
                        'Votos' => number_format($eleicao['votos'], 0, ',', '.') . ' (' . $porcentagemFormatada . '%)',
                        'Gráfico' => $barraProgresso
                    ];
                }

                echo $layoutClass->criarTabela($tabela);

                ?>

                <div class="card mb-2">
                    <div class="card-body p-2">
                        <p class="mb-0">Total de votos em <?php echo $ano ?>:
                            <b><?php echo number_format($totalVotos, 0, ',', '.') ?></b>
                        </p>
                    </div>
                </div>



            </div>
        </div>
    </div>
</body>

</html>