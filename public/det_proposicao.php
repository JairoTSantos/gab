<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';
require_once dirname(__DIR__) . '/app/core/GetJson.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/ProposicaoController.php';
$proposicaoController = new ProposicaoController();

require_once dirname(__DIR__) . '/app/controllers/NotaTecnicaController.php';
$notaTecnicaController = new NotaTecnicaController();

$proposicaoId = $_GET['proposicao'];
$proposicao = $proposicaoController->BuscarProposicao($proposicaoId)['dados'];

$paginaTramitacaoAtual = $_GET['paginaTramitacao'] ?? 1;
$nota = $notaTecnicaController->BuscarNotaTecnica('nota_proposicao', $proposicaoId);



?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Detalhe da proposição'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar(true, 'proposicoes.php') ?>
                <?php $layoutClass->cardDescription('<i class="fa-regular fa-file-lines"></i> Detalhes da proposição', '<p class="card-text mb-0">Veja os detalhes, tramitações, proposições relacionadas entre outros.</p>') ?>
                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2" style="font-size:0.850em">
                                <h5 class="card-title mb-0"><?php echo $proposicao['proposicao_titulo'] ?></h5>
                                

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>