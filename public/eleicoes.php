<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/EleicoesController.php';

$eleicoesController = new EleicoesController()


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
                <?php $layoutClass->cardDescription(
                    '<i class="fa-solid fa-user-plus"></i> Eleições Disputadas',
                    '<p class="card-text mb-2">Lista de todas as eleições em que o deputado participou.</p><p class="card-text mb-0">Clique no ano da eleição para visualizar os respectivos resultados.</p>'
                ) ?>


                <?php

                $cargos = $eleicoesController->getCargosDisputados();
                $tabela = [];
                if ($cargos['status'] == 'success') {
                    $tabela = [];

                    foreach ($cargos['dados'] as $cargo) {
                        $tabela[] = [
                            'Ano' => '<a href="resultado_eleicao.php?eleicao=' . $cargo['CD_ELEICAO'] . '&ano='.$cargo['ANO_ELEICAO'].'&cargo=' . $cargo['CD_CARGO'] . '">' . $cargo['ANO_ELEICAO'] . '</a>',
                            'Cargo' => $cargo['DS_CARGO'],
                            'Votos' => number_format($cargo['votos_validos'], 0, ',', '.'),
                            'Resultado' => (stripos($cargo['DS_SIT_TOT_TURNO'], 'ELEITO') !== false) ? '<b style="color:green">Eleito</b>' : '<b style="color:red">Não eleito'
                        ];
                    }

                    echo $layoutClass->criarTabela($tabela);
                } else if ($cargos['status'] == 'empty') {
                    echo $layoutClass->criarTabela([]);
                } else if ($cargos['status'] == 'error') {
                    echo $layoutClass->criarTabela([['Mensagem' => 'Erro interno do servidor.']]);
                }

                ?>



            </div>
        </div>
    </div>
    </div>
</body>

</html>