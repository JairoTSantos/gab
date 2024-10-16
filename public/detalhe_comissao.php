<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/core/GetJson.php';


require_once dirname(__DIR__) . '/app/controllers/ComissoesController.php';
$comissoesController = new ComissoesController();

$comissao = $_GET['comissao'];
$tipo = $_GET['tipo'] ?? 101;


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
                <?php $layoutClass->navBar(true, 'comissoes.php') ?>
                <?php $layoutClass->cardDescription(
                    '<i class="fa-solid fa-building"></i> Detalhes da Comissão',
                    '<p class="card-text mb-2">Nesta seção, você encontrará informações detalhadas sobre a comissão.</p>
                     <p class="card-text mb-0">Serão exibidos todos os cargos que o deputado já ocupou nesta comissão, bem como os cargos da mesa e a lista de membros titulares e suplentes.</p>'
                ) ?>
                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2 ">
                            <div class="card-body p-0">
                                <nav class="navbar navbar-expand bg-body-tertiary p-0 ">
                                    <div class="container-fluid p-0">
                                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                            <ul class="navbar-nav me-auto mb-0 mb-lg-0">
                                                <li class="nav-item">
                                                    <a class="nav-link active p-1" aria-current="page" href="agenda_comissao.php?comissao=<?php echo $comissao ?>">
                                                        <button class="btn btn-success btn-sm" style="font-size: 0.850em;" id="btn_novo_tipo" type="button">
                                                            <i class="fa-solid fa-calendar-days"></i> Ver agenda da comissão
                                                        </button>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="card card_description">
                            <div class="card-header px-2 py-1 bg-primary text-white" style="font-size:1em">
                                Cargos ocupados pelo deputado
                            </div>
                            <div class="card-body p-2">
                                <?php

                                $cargos = $comissoesController->ListarCargos($comissao);

                                if ($cargos['status'] == 'success') {
                                    echo '<div class="list-group"  style="font-size:1em">';
                                    foreach ($cargos['dados'] as $cargo) {
                                        echo '<a href="#" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-2"  style="font-size:1.2em">' . $cargo['comissao_cargo'] . '</h6>
                                                </div>
                                                <p class="mb-0"><small>Início: ' . date('d/m/Y', strtotime($cargo['comissao_inicio'])) . '</small></p>
                                                <p class="mb-0">' . (isset($cargo['comissao_fim']) ? '<small>Saída: ' . date('d/m/Y', strtotime($cargo['comissao_fim'])) . '</small>' : '<small>Saída: Membro atualmente') . '</small></p>
                                              </a>';
                                    }
                                    echo '</div>';
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12">
                        <div class="card card_description">
                            <div class="card-header px-2 py-1 bg-secondary text-white" style="font-size:1em">
                                Membros dessa comissão
                            </div>
                            <div class="card-body p-2">
                                <?php
                                $membros = getJson('https://dadosabertos.camara.leg.br/api/v2/orgaos/' . $comissao . '/membros?dataInicio=' . date('Y-m-d') . '&dataFim=' . date('Y-m-d') . '&itens=100');

                                if (isset($membros['dados'])) {
                                    $presidente = 'Não há';
                                    $id_presidente = 0;
                                    $vice1 = 'Não há';
                                    $vice2 = 'Não há';
                                    $vice3 = 'Não há';

                                    foreach ($membros['dados'] as $mesa) {
                                        if ($mesa['codTitulo'] == 1) {
                                            $presidente = $mesa['nome'] . ' ' . $mesa['siglaPartido'] . '/' . $mesa['siglaUf'];
                                            $id_presidente = $mesa['id'];
                                        }
                                        if ($mesa['codTitulo'] == 2) {
                                            $vice1 = $mesa['nome'] . ' ' . $mesa['siglaPartido'] . '/' . $mesa['siglaUf'];
                                            $id_vice1 = $mesa['id'];
                                        }
                                        if ($mesa['codTitulo'] == 3) {
                                            $vice2 = $mesa['nome'] . ' ' . $mesa['siglaPartido'] . '/' . $mesa['siglaUf'];
                                            $id_vice2 = $mesa['id'];
                                        }
                                        if ($mesa['codTitulo'] == 4) {
                                            $vice3 = $mesa['nome'] . ' ' . $mesa['siglaPartido'] . '/' . $mesa['siglaUf'];
                                            $id_vice3 = $mesa['id'];
                                        }
                                    }
                                ?>
                                    <p class="card-text mb-2"><i class="fa-solid fa-caret-right"></i> Presidente: | <a href="https://www.camara.leg.br/deputados/<?php echo $id_presidente; ?>" target="_blank"><?php echo $presidente; ?></a></p>

                                    <?php if ($vice1 != 'Não há'): ?>
                                        <p class="card-text mb-0"><i class="fa-solid fa-caret-right"></i> 1º Vice-presidente: | <a href="https://www.camara.leg.br/deputados/<?php echo $id_vice1; ?>" target="_blank"><?php echo $vice1; ?></a></p>
                                    <?php endif; ?>

                                    <?php if ($vice2 != 'Não há'): ?>
                                        <p class="card-text mb-0"><i class="fa-solid fa-caret-right"></i> 2º Vice-presidente: | <a href="https://www.camara.leg.br/deputados/<?php echo $id_vice2; ?>" target="_blank"><?php echo $vice2; ?></a></p>
                                    <?php endif; ?>

                                    <?php if ($vice3 != 'Não há'): ?>
                                        <p class="card-text mb-0"><i class="fa-solid fa-caret-right"></i> 3º Vice-presidente: |<a href="https://www.camara.leg.br/deputados/<?php echo $id_vice3; ?>" target="_blank"> <?php echo $vice3; ?></a></p>
                                    <?php endif; ?>

                                <?php
                                } else {
                                    echo '<p class="card-text mb-1">Erro ao buscar informações. <a href="detalhe_comissao.php?comissao=' . $comissao . '">Tentar novamente</a></p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <form class="row g-2 form_custom mb-0" method="GET" enctype="application/x-www-form-urlencoded">
                                    <input type="hidden" value="<?php echo $comissao ?>" name="comissao" />
                                    <div class="col-md-2 col-10">
                                        <select class="form-select form-select-sm" name="tipo" required>
                                            <option value="101" <?php echo $tipo == 101 ? 'selected' : ''; ?>>Titulares</option>
                                            <option value="102" <?php echo $tipo == 102 ? 'selected' : ''; ?>>Suplentes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-2">
                                        <button type="submit" class="btn btn-success btn-sm"><i
                                                class="fa-solid fa-magnifying-glass"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $tabela_membros = [];
                if (isset($membros['dados'])) {
                    foreach ($membros['dados'] as $membro) {
                        if ($membro['codTitulo'] == $tipo) {
                            $tabela_membros[] = [
                                'Deputado(a)' => '<a href="https://www.camara.leg.br/deputados/' . $membro['id'] . '" target="_blank">' . $membro['nome'] . '</a>',
                                'Partido' => $membro['siglaPartido'] . '/' . $membro['siglaUf']
                            ];
                        }
                    }
                    usort($tabela_membros, function ($a, $b) {
                        return strcmp(strip_tags($a['Deputado(a)']), strip_tags($b['Deputado(a)']));
                    });

                    echo $layoutClass->criarTabela($tabela_membros);
                } else {
                    echo $layoutClass->criarTabela([['Informação' => 'Erro interno do servidor']]);
                }
                ?>
            </div>
        </div>
    </div>


</body>

</html>