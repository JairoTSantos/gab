<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/core/GetJson.php';


require_once dirname(__DIR__) . '/app/controllers/ComissoesController.php';
$comissoesController = new ComissoesController();

$comissao = $_GET['comissao'];
$tipo = $_GET['tipo'] ?? 101;


$comissaoDet = $comissoesController->DetalhesComissao($comissao);

if ($comissaoDet['status'] != 'success') {
    header('Location: comissoes.php');
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Detalhe Comissão'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar(true, 'comissoes.php') ?>
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="card card_description">
                            <div class="card-header px-2 py-1 bg-primary text-white card-background" style="font-size:1em">
                                Detalhes da comissão
                            </div>
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1" style="font-size:1.4em"><?php echo $comissaoDet['dados'][0]['comissao_sigla'] ?></h6>
                                <p class="card-text"><?php echo $comissaoDet['dados'][0]['comissao_nome_publicacao'];  ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2 ">
                            <div class="card-body p-0">
                                <nav class="navbar navbar-expand bg-body-tertiary p-0 ">
                                    <div class="container-fluid p-0">
                                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                            <ul class="navbar-nav me-auto mb-0 mb-lg-0">
                                                <li class="nav-item">
                                                    <a class="nav-link active p-1" aria-current="page" href="agenda_comissao.php?comissao=<?php echo $comissaoDet['dados'][0]['comissao_id'];  ?>">
                                                        <button class="btn btn-outline-success btn-sm" style="font-size: 0.850em;" id="btn_novo_tipo" type="button">
                                                            <i class="fa-solid fa-calendar-days"></i> Agenda da comissão
                                                        </button>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link active p-1" aria-current="page" href="<?php echo $comissaoDet['dados'][0]['comissao_site'];  ?>" target="_blank">
                                                        <button class="btn btn-outline-secondary btn-sm" style="font-size: 0.850em;" id="btn_novo_tipo" type="button">
                                                            <i class="fa-solid fa-calendar-days"></i> Página
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
                <?php

                $cargos = $comissoesController->ListarCargos($comissao);

                $tabela_cargos = [];
                if ($cargos['status'] == 'success') {
                    foreach ($cargos['dados'] as $cargo) {
                        $tabela_cargos[] = [
                            'Cargo' => $cargo['comissao_cargo'],
                            'Início' => '  <i class="fa-solid fa-right-long"></i> ' . date('d/m/Y', strtotime($cargo['comissao_entrada'])),
                            'Saída' => (!empty($cargo['comissao_saida']) ? date('d/m/Y', strtotime($cargo['comissao_saida'])) . '  <i class="fa-solid fa-right-long"></i>' : 'Membro')
                        ];
                    }

                    echo $layoutClass->criarTabela($tabela_cargos);
                } else if ($cargos['status'] == 'error') {
                    echo $layoutClass->criarTabela([['Mensagem' => 'Erro interno do servidor.']]);
                } else {
                    echo $layoutClass->criarTabela([['Mensagem' => 'O deputado nunca foi membro dessa comissão.']]);
                }


                ?>
                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-header px-2 py-1 bg-secondary text-white" style="font-size:1em">
                                Membros dessa comissão
                            </div>
                            <div class="card-body p-2" style="font-size: 0.950em;">
                                <?php
                                $jsonData = [];
                                $dadosJson = getJson('https://dadosabertos.camara.leg.br/api/v2/orgaos/' . $comissao . '/membros?dataInicio=' . date('Y-m-d') . '&itens=100');

                                if (!isset($dadosJson['error'])) {
                                    foreach ($dadosJson['dados'] as $dep) {
                                        if ($dep['codTitulo'] == 1) {
                                            echo '<p class="card-text mb-0"><i class="fa-solid fa-user-tie"></i> | Presidente: ' . $dep['nome'] . ' ' . $dep['siglaPartido'] . '/' . $dep['siglaUf'] . '</p>';
                                        } else if ($dep['codTitulo'] == 2) {
                                            echo '<p class="card-text mb-0"><i class="fa-solid fa-user-tie"></i> | 1º Vice: ' . $dep['nome'] . ' ' . $dep['siglaPartido'] . '/' . $dep['siglaUf'] . '</p>';
                                        } else if ($dep['codTitulo'] == 3) {
                                            echo '<p class="card-text mb-0"><i class="fa-solid fa-user-tie"></i> | 2º Vice: ' . $dep['nome'] . ' ' . $dep['siglaPartido'] . '/' . $dep['siglaUf'] . '</p>';
                                        } else if ($dep['codTitulo'] == 4) {
                                            echo '<p class="card-text mb-0"><i class="fa-solid fa-user-tie"></i> | 3º Vice: ' . $dep['nome'] . ' ' . $dep['siglaPartido'] . '/' . $dep['siglaUf'] . '</p>';
                                        }
                                    }
                                } else {
                                    echo '<p class="card-text mb-0">Erro interno do servidor</p>';
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
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php


                $registrosPorPagina = 10;
                $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

                $tabela_membros = [];
                if (!isset($dadosJson['error'])) {

                    foreach ($dadosJson['dados'] as $dep) {
                        if ($dep['codTitulo'] == $tipo) {
                            $tabela_membros[] = [
                                'Deputado' => $dep['nome'],
                                'Partido' => $dep['siglaPartido'] . '/' . $dep['siglaUf']
                            ];
                        }
                    }

                    usort($tabela_membros, function ($a, $b) {
                        return strcmp($a['Deputado'], $b['Deputado']);
                    });

                    $totalRegistros = count($tabela_membros);

                    $inicio = ($paginaAtual - 1) * $registrosPorPagina;
                    $dadosPaginados = array_slice($tabela_membros, $inicio, $registrosPorPagina);

                    echo $layoutClass->criarTabela($dadosPaginados);

                    $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

                    if ($totalPaginas > 1) {
                        echo '<nav>';
                        echo '<ul class="pagination custom-pagination ">';

                        // Botão "Anterior"
                        if ($paginaAtual > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?pagina=' . ($paginaAtual - 1) . '&comissao=' . $comissao . '&tipo=' . $tipo . '">Anterior</a></li>';
                        } else {
                            echo '<li class="page-item disabled"><a class="page-link">Anterior</a></li>';
                        }

                        // Botões das páginas
                        for ($i = 1; $i <= $totalPaginas; $i++) {
                            if ($i == $paginaAtual) {
                                echo '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
                            } else {
                                echo '<li class="page-item"><a class="page-link" href="?pagina=' . $i . '&comissao=' . $comissao . '&tipo=' . $tipo . '">' . $i . '</a></li>';
                            }
                        }

                        // Botão "Próximo"
                        if ($paginaAtual < $totalPaginas) {
                            echo '<li class="page-item"><a class="page-link" href="?pagina=' . ($paginaAtual + 1) . '&comissao=' . $comissao . '&tipo=' . $tipo . '">Próximo</a></li>';
                        } else {
                            echo '<li class="page-item disabled"><a class="page-link">Próximo</a></li>';
                        }

                        echo '</ul>';
                        echo '</nav>';
                    }
                } else {
                    echo $layoutClass->criarTabela([['Mensagem' => 'Erro interno do servidor.']]);
                }

                ?>


            </div>
        </div>
    </div>


</body>

</html>