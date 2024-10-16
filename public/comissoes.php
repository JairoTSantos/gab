<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/ComissoesController.php';
$comissoesController = new ComissoesController();
$comissoesController->atualizarComissoes();

$flag = isset($_GET['flag']) ? filter_var($_GET['flag'], FILTER_VALIDATE_BOOLEAN) : false;


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
                <?php $layoutClass->navBar() ?>
                <?php $layoutClass->cardDescription(
                    '<i class="fa-solid fa-building"></i> Comissões do Deputado',
                    '<p class="card-text mb-0">Nesta seção, você pode visualizar as comissões das quais o deputado é membro e aquelas das quais já foi membro.</p>'
                ) ?>

                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <form class="row g-2 form_custom mb-0" method="GET" enctype="application/x-www-form-urlencoded">

                                    <div class="col-md-2 col-6">

                                        <select class="form-select form-select-sm" name="flag" required>
                                            <option value="false" <?php echo !$flag ? 'selected' : ''; ?>>Somente atuais</option>
                                            <option value="true" <?php echo $flag ? 'selected' : ''; ?>>Ver todas</option>
                                        </select>
                                    </div>

                                    <div class="col-md-1 col-6">
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $comissoes = $comissoesController->listarComissoes($flag);
                $tabela = [];

                if ($comissoes['status'] == 'success') {
                    foreach ($comissoes['dados'] as $comissao) {
                        if ($comissao['comissao_sigla'] !== 'PLEN' && $comissao['comissao_sigla'] !== 'PLENARIO') {
                            $tabela[] = [
                                "Sigla" => '<a href="detalhe_comissao.php?comissao=' . $comissao['comissao_id'] . '">' . $comissao['comissao_sigla'] . '</a>',
                                "Comissao" => $comissao['comissao_nome']
                            ];
                        }
                    }
                    echo $layoutClass->criarTabela($tabela);
                }
                ?>

            </div>
        </div>
    </div>
    </div>
</body>

</html>