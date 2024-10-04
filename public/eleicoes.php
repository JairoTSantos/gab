<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/app/controllers/EleicoesController.php';
$eleicoesController = new EleicoesController(2022);

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
    <?php $layoutClass->MontarHead('Eleições'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
            <?php $layoutClass->navBar() ?>
                <?php $layoutClass->cardDescription('<i class="fa-solid fa-user-plus"></i> Resultado das eleições', '<p class="card-text mb-2">Seção para conferência dos resultados das eleições.</p><p class="card-text mb-2">Aqui você poderá verificar os resultados por município.</p><p class="card-text mb-0">Os dados apresentados são provenientes do Tribunal Superior Eleitoral (TSE) e estão sujeitos a revisão e conferência final.</p>') ?>

                <?php


                $resultado = $eleicoesController->PorMunicipio();
                $totalVotos = $resultado['total_votos'];
                $novoArray = [];


                foreach ($resultado as $key => $value) {
                    
                    if ($key === 'total_votos') {
                        continue;
                    }

                    
                    $porcentagem = ($value['QT_VOTOS'] / $totalVotos) * 100;

                    
                    $novoArray[] = [
                        'Municipio' => '<a href="#'.$value['CD_MUNICIPIO'].'">'.$value['NM_MUNICIPIO'].'</a>',
                        'Votos' => $value['QT_VOTOS'], // Armazena o valor original para ordenação
                        '% do total de votos' => round($porcentagem, 1) . '%'
                    ];
                }
                
                usort($novoArray, function ($a, $b) {
                    return $b['Votos'] <=> $a['Votos']; // Ordena usando o valor original
                });
                
                foreach ($novoArray as &$municipio) {
                    $municipio['Votos'] = number_format($municipio['Votos'], 0, ',', '.');
                }

                ?>

                <div class="card mb-2">
                    <div class="card-body p-2">
                        <h4 class="mb-0">Total de votos: <?php echo number_format($totalVotos, 0, ',', '.'); ?> | 100%</h4>
                    </div>
                </div>

                <?php
                echo $layoutClass->criarTabela($novoArray);




                ?>

            </div>
        </div>
    </div>
</body>

</html>