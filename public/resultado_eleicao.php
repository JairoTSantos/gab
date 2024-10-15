<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/EleicoesController.php';
$eleicoesController = new EleicoesController();

$eleicao = $_GET['eleicao'];
$ano = $_GET['ano'];
$cargo = $_GET['cargo'];


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
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar(true, 'eleicoes.php') ?>
                <?php $layoutClass->cardDescription('Detalhes da Eleição', '<p class="card-text mb-2">Visualize os resultados do deputado na eleição selecionada.</p><p class="card-text mb-0">Aqui você encontrará a contagem dos votos nominais (aqueles direcionados a candidatos específicos) e dos votos de legenda.</p>');

                $detalhes = $eleicoesController->getDetalhesEleicao($ano, $eleicao, $cargo);

                ?>

                <div class="row mb-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-2">
                                <?php
                                echo '<h5 class="card-title">' . $detalhes['dados']['DS_CARGO'] . '</h5>';
                                $total_votos_validos = $detalhes['dados']['total_votos_validos'];
                                $total_brancos = $detalhes['dados']['total_votos_brancos'];
                                $total_nulos = $detalhes['dados']['total_votos_nulos'];
                                $total = $total_votos_validos + $total_brancos + $total_nulos;

                                // Calcular porcentagens em relação ao total
                                $porcentagem_validos = ($total_votos_validos / $total) * 100;
                                $porcentagem_brancos = ($total_brancos / $total) * 100;
                                $porcentagem_nulos = ($total_nulos / $total) * 100;
                                ?>

                                <p class="card-text mb-0">Votos nominais: <?php echo number_format($total_votos_validos, 0, ',', '.') ?> | <small>(<?php echo number_format($porcentagem_validos, 2, ',', '.') ?>%)</small></p>
                                <p class="card-text mb-0">Brancos: <?php echo number_format($total_brancos, 0, ',', '.') ?> | <small>(<?php echo number_format($porcentagem_brancos, 2, ',', '.') ?>%)</small></p>
                                <p class="card-text mb-3">Nulos: <?php echo number_format($total_nulos, 0, ',', '.') ?> | <small>(<?php echo number_format($porcentagem_nulos, 2, ',', '.') ?>%)</small></p>

                                <p class="card-text mb-0">Total: <?php echo number_format($total, 0, ',', '.') ?> | <small>(100%)</small></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-2">
                                <p class="card-text mb-0"><b>Votos do deputado</b></p>

                            </div>
                        </div>
                    </div>
                </div>

                <?php

                $resultados = $eleicoesController->getResultadoEleicao($ano, $eleicao);
                $tabela = [];
                $total_dep = 0;
                if ($resultados['status'] == 'success') {
                    foreach ($resultados['dados'] as $resultado) {
                        $porcentagem = ($resultado['total_votos'] / $total_votos_validos) * 100;
                        $total_dep = $total_dep + $resultado['total_votos'];
                        $tabela[] = [
                            'Municipio' => $resultado['NM_MUNICIPIO'],
                            'Votos (% votos nominais)' => number_format($resultado['total_votos'], 0, ',', '.') . ' <small>(' . number_format($porcentagem, 2, ',', '.') . '%)</small>'
                        ];
                    }
                    echo $layoutClass->criarTabela($tabela);
                } else if ($resultados['status'] == 'error') {
                    echo $layoutClass->criarTabela([['Mensagem' => 'Erro interno do servidor.']]);
                } else {
                    echo $layoutClass->criarTabela([]);
                }

                ?>

                <div class="row mb-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-2">
                                Total de votos: <b><?php echo number_format($total_dep, 0, ',', '.'); ?> | <?php echo number_format(($total_dep / $total_votos_validos) * 100, 2, ',', '.') ?>% </b> <small>(votos nominais no estado)</small>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>