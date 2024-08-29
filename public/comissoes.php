<?php
require_once __DIR__ . '/includes/verificaLogado.php';
require_once __DIR__ . '/includes/layout.php';
require_once dirname(__DIR__) . '/app/controllers/ComissoesController.php';

$comissoesController = new ComissoesController();

$todasComissoes = filter_var($_GET['todasComissoes'] ?? '0', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php montarHeader('Comissões'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php include 'includes/side_menu.php' ?>
        <div id="page-content-wrapper">
            <?php include 'includes/top_menu.php' ?>
            <div class="container-fluid p-2">
                <?php navBar(); ?>
                <?php cardDescription('<i class="fa-solid fa-building-columns"></i> Comissões', '<p class="card-text mb-0">Comissões em que o deputado é ou já foi membro: Escolha se deseja visualizar o histórico completo de comissões ou apenas as atuais.</p>'); ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <form class="row g-2 form_custom mb-0" method="GET" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-2 col-10">
                                        <select class="form-select form-select-sm" name="todasComissoes" required>
                                            <option value="0" <?php echo $todasComissoes === false ? 'selected' : ''; ?>>Mostrar somente atuais</option>
                                            <option value="1" <?php echo $todasComissoes === true ? 'selected' : ''; ?>>Mostrar todas</option>
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
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body p-2">
                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                    <?php
                                    $comissoes = $comissoesController->BuscarComissaoDeputados($todasComissoes);
                                    foreach ($comissoes as $idOrgao => $comissoes) {
                                        echo '
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" style="font-size:0.450em" type="button" data-bs-toggle="collapse" data-bs-target="#id' . $idOrgao . '" aria-expanded="true" aria-controls="id' . $idOrgao . '">
                                               ' . $comissoes['comissao_sigla'] . ' - ' . $comissoes['comissao_apelido'] . '
                                            </button>
                                            </h2>
                                            <div id="id' . $idOrgao . '" class="accordion-collapse collapse ">
                                            <div class="accordion-body">';
                                                foreach($comissoes['comissao_cargos'] as $comissao){
                                                    echo '<p style="font-size:1em" class="mb-1">Cargo: ' . $comissao['cargo_titulo'] . '</p>';
                                                    echo '<p style="font-size:1em" class="mb-1"><i class="fa-solid fa-arrow-right"></i> Início: ' .date('d/m/Y', strtotime($comissao['cargo_data_inicio']))  . '</p>';
                                                    echo '<p style="font-size:1em" class="mb-1"><i class="fa-solid fa-arrow-left"></i> Fim: ' . ($comissao['cargo_data_fim'] ? date('d/m/Y', strtotime($comissao['cargo_data_fim'])) : '<b>Membro atual</b>') . '</p><hr>';

                                                }
                                            echo '</div>
                                            </div>
                                        </div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>