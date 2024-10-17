<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/ComissoesController.php';
$comissoesController = new ComissoesController();

$flag = isset($_GET['flag']) ? filter_var($_GET['flag'], FILTER_VALIDATE_BOOLEAN) : false;
$tipo_comissao = $_GET['tipo'] ?? 2;


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Comissões'); ?>
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
                    '<p class="card-text mb-2">Aqui, você pode visualizar as comissões em que o deputado é membro e aquelas das quais já foi membro.</p>
                     <p class="card-text mb-0"><i class="fa-solid fa-triangle-exclamation"></i> <b>As informações são de responsabilidade da Câmara dos Deputados.</b></p>'
                ) ?>

                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-header px-2 py-1 bg-primary text-white" style="font-size:1em">
                                Comissões do deputado
                            </div>
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
                $comissoes_dep = $comissoesController->ListarComissoesDep($flag);
                $tabela_dep = [];
                if ($comissoes_dep['status'] == 'success') {
                    foreach ($comissoes_dep['dados'] as $comissao_dep) {
                        if ($comissao_dep['comissao_sigla'] !== 'PLEN' && $comissao_dep['comissao_sigla'] !== 'PLENARIO') {
                            $tabela_dep[] = [
                                "Sigla" => '<a href="detalhe_comissao.php?comissao=' . $comissao_dep['comissao_id'] . '">' . $comissao_dep['comissao_sigla'] . '</a>',
                                "Comissao" => $comissao_dep['comissao_nome_publicacao'],
                                "Tipo" => $comissao_dep['comissao_descricao']
                            ];
                        }
                    }
                    echo $layoutClass->criarTabela($tabela_dep);
                }
                ?>

                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-header px-2 py-1 bg-secondary text-white" style="font-size:1em">
                                Comissões
                            </div>
                            <div class="card-body p-2">
                                <form class="row g-2 form_custom mb-0" method="GET" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-2 col-10">
                                        <select class="form-select form-select-sm" name="tipo" required>
                                            <?php
                                            $tipos = $comissoesController->ListarTiposComissoes();
                                            if ($tipos['status'] == 'success') {
                                                foreach ($tipos['dados'] as $tipo) {
                                                    if ($tipo['comissao_tipo'] == $tipo_comissao) {
                                                        echo '<option value="' . $tipo['comissao_tipo'] . '" selected>' . $tipo['comissao_descricao'] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $tipo['comissao_tipo'] . '">' . $tipo['comissao_descricao'] . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-2">
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $comissoes = $comissoesController->ListarComissoes($tipo_comissao);
                $tabela_comissoes = [];
                if ($comissoes['status'] == 'success') {
                    foreach ($comissoes['dados'] as $comissao) {
                        $tabela_comissoes[] = [
                            "Sigla" => '<a href="detalhe_comissao.php?comissao=' . $comissao['comissao_id'] . '">' . $comissao['comissao_sigla'] . '</a>',
                            "Comissao" => $comissao['comissao_apelido'],
                            "Descricao" => $comissao['comissao_nome']
                        ];
                    }
                    echo $layoutClass->criarTabela($tabela_comissoes);
                }

                ?>

            </div>
        </div>
    </div>
    </div>
</body>

</html>