<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/core/GetJson.php';


$data = isset($_GET['data']) ? htmlspecialchars($_GET['data']) : date('Y-m-d');
$comissao = $_GET['comissao'];
$tipo = $_GET['tipo'] ?? 0;


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Agenda da comissão'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->cardDescription(
                    '<i class="fa-solid fa-building"></i> Agenda da comissão',
                    '<p class="card-text mb-0">Agenda de reuniões de uma comissão selecionada.</p>'
                ) ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <form class="row g-2 form_custom mb-0" method="GET" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-2 col-4">
                                        <input type="date" class="form-control form-control-sm" value="<?php echo $data; ?>" name="data">
                                        <input type="hidden" class="form-control form-control-sm" value="<?php echo $comissao; ?>" name="comissao">
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <select class="form-select form-select-sm" name="tipo" required>
                                            <option value="112" <?php echo ($tipo == 112) ? 'selected' : ''; ?>>Reuniões deliberativas</option>
                                            <option value="207" <?php echo ($tipo == 207) ? 'selected' : ''; ?>>Reunião de Comparecimento de Ministro(a)</option>
                                            <option value="0" <?php echo ($tipo == 0) ? 'selected' : ''; ?>>Todas as reuniões</option>
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
                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <div class="list-group">


                                    <?php

                                    if ($tipo == 0) {
                                        $dadosJson = getJson('https://dadosabertos.camara.leg.br/api/v2/orgaos/' . $comissao . '/eventos?dataInicio=' . $data . '&dataFim=' . $data . '&ordem=ASC&ordenarPor=dataHoraInicio&itens=100');
                                    } else {
                                        $dadosJson = getJson('https://dadosabertos.camara.leg.br/api/v2/orgaos/' . $comissao . '/eventos?dataInicio=' . $data . '&dataFim=' . $data . '&ordem=ASC&ordenarPor=dataHoraInicio&itens=100&idTipoEvento=' . $tipo);
                                    }



                                    //print_r($dadosJson);

                                    if (!isset($dadosJson['error'])) {
                                        if (count($dadosJson['dados']) > 0) {
                                            foreach ($dadosJson['dados'] as $reuniao) {
                                                echo ' <a href="#" class="list-group-item list-group-item-action" >
                                                        <p class="mb-1"><i class="fa-regular fa-clock" style="font-size:0.850em"></i> ' . date('H:i', strtotime($reuniao['dataHoraInicio'])) . '</p>
                                                        <p class="mb-1" ><i class="fa-regular fa-building" style="font-size:0.850em"></i> ' . $reuniao['localCamara']['nome'] . '</p>
                                                        <p class="mb-2">' . $reuniao['descricaoTipo'] . ' | ' . $reuniao['situacao'] . '</p>
                                                         <small class="text-body-secondary" style="font-size:0.8em">' . $reuniao['descricao'] . '</small>
                                                    </a>';
                                            }
                                        } else {
                                            echo ' <a href="#" class="list-group-item list-group-item-action" >
                                                        <p class="mb-0">Nenhuma reunião para a data ou tipo selecionado</p>
                                                    </a>';
                                        }
                                    } else {
                                        echo ' <a href="#" class="list-group-item list-group-item-action" >
                                                    <p class="mb-0">Não foi possível buscar os dados</p>
                                                </a>';
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
    </div>
</body>

</html>