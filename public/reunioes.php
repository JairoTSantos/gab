<?php
require_once __DIR__ . '/includes/verificaLogado.php';
require_once __DIR__ . '/includes/layout.php';
require_once dirname(__DIR__) . '/app/controllers/ComissoesController.php';

$comissoesController = new ComissoesController();

$todasComissoes = isset($_GET['todasComissoes']) ? ($_GET['todasComissoes'] == "1") : false;
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'Reunião Deliberativa';



$data = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php montarHeader('Reuniões das comissões'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php include 'includes/side_menu.php' ?>
        <div id="page-content-wrapper">
            <?php include 'includes/top_menu.php' ?>
            <div class="container-fluid p-2">
                <?php navBar(); ?>
                <?php cardDescription('<i class="fa-solid fa-calendar-days"></i> Agenda das comissões', '<p class="card-text mb-0">Veja todas as reuniões deliberativas agendadas para hoje</p>'); ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <form class="row g-2 form_custom mb-0" method="GET" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" name="todasComissoes" required>
                                            <option value="0" <?php echo !$todasComissoes ? "selected" : ""; ?>>Mostrar somente membro</option>
                                            <option value="1" <?php echo $todasComissoes ? "selected" : ""; ?>>Mostrar todas as comissões</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" name="tipo" required>
                                            <option value="Reunião Deliberativa" <?php echo $tipo == 'Reunião Deliberativa' ? 'selected' : ''; ?>>Reuniões Deliberativas</option>
                                            <option value="Audiência Pública" <?php echo $tipo == 'Audiência Pública' ? 'selected' : ''; ?>>Audiências Públicas</option>

                                        </select>
                                    </div>
                                    <div class="col-md-1 col-6">
                                        <input type="date" class="form-control form-control-sm" value="<?php echo $data ?>" name="data">
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
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                    <?php
                                    $reunioes = $comissoesController->BuscarReunioes($todasComissoes, $data, $tipo);

                                    if (!empty($reunioes)) {
                                        foreach ($reunioes as $hora => $reunioes) {
                                            $horaFormatada = date('H-i', strtotime($hora));
                                            echo '
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header">
                                                            <button class="accordion-button collapsed"  style="font-size:0.450em" type="button" data-bs-toggle="collapse" data-bs-target="#id' . $horaFormatada . '" aria-expanded="true" aria-controls="id' . $horaFormatada . '">
                                                                <i class="fa-regular fa-clock"></i> &nbsp;&nbsp;' . date('H:i', strtotime($hora)) . '
                                                            </button>
                                                        </h2>
                                                        <div id="id' . $horaFormatada . '" class="accordion-collapse collapse">
                                                            <div class="accordion-body">';
                                                                foreach ($reunioes as $reuniao) {
                                                                    echo '<p style="font-size:1em" class="mb-1"><i class="fa-solid fa-building-columns"></i> <b>' . $reuniao['reuniao_orgao'][0]['sigla'] . ' - ' . $reuniao['reuniao_orgao'][0]['nomePublicacao'] . '</b></p>';
                                                                    echo '<p style="font-size:1em" class="mb-1">' . $reuniao['reuniao_tipo'] . '</p>';
                                                                    echo '<p style="font-size:0.9em" class="mb-1">' . $reuniao['reuniao_descricao'] . ' | ' . $reuniao['reuniao_local']['nome'] . '</p>';
                                                                    echo '<p style="font-size:1.1em" class="mb-2"><b>' . $reuniao['reuniao_situacao'] . '</b></p>';
                                                                    echo '<p style="font-size:0.9em" class="mb-0">' . (!empty($reuniao['reuniao_video']) ? '<a href="' . $reuniao['reuniao_video'] . '" target="_blank"><i class="fa-brands fa-youtube"></i> Ver reunião | </a>' : '') . '';
                                                                    echo '  <a href="./pauta/' . $reuniao['reuniao_id'] . '"><i class="fa-regular fa-file-lines"></i> Ver pauta</a></p><hr>';
                                                                }
                                                                echo ' </div>
                                                        </div>
                                                    </div>';
                                        }
                                    } else {
                                        echo '<p class="card-text">Nenhuma reunião encontrada</p>';
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