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
                                <h5 class="card-title mb-2"><?php echo $proposicao['proposicao_titulo'] ?></h5>
                                <p class="card-text mb-2"><em><?php echo $proposicao['proposicao_ementa'] ?></em></p>
                                <p class="card-text mb-0">Data da apresentação: <?php echo date('d/m/Y', strtotime($proposicao['proposicao_apresentacao'])) ?></p>
                                <?php echo $proposicao['proposicao_arquivada'] ? '<p class="card-text mb-0 mt-2"><b><i class="fa-solid fa-circle-exclamation"></i> Proposicão Arquivada</b></p>' : ''  ?>
                                <?php echo $proposicao['proposicao_norma'] ? '<p class="card-text mb-0"><b>Proposicão Transformada em norma jurídica</b></p>' : ''  ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-header bg-primary text-white px-2 py-1" style="font-size:0.950em"><i class="fa-regular fa-file-lines"></i> Nota técnica</div>
                            <div class="card-body p-2" style="font-size:0.850em">
                                <?php

                                $nota = $notaTecnicaController->BuscarNotaTecnica('nota_proposicao', $proposicaoId);

                                if ($nota['status'] == 'empty') {
                                    echo '<p class="card-text mb-0"><a type="button" href="nota_tecnica.php?proposicao=' . $proposicao['proposicao_id'] . '" class="btn btn-success btn-sm" style="font-size:0.9em"><i class="fa-solid fa-plus"></i> Nova nota</a></p>';
                                } else if ($nota['status'] == 'success') {
                                    echo '<p class="card-text mb-2" style="font-size:1.2em">' . $nota['dados']['nota_titulo'] . '</p>';
                                    echo '<p class="card-text mb-2" style="font-size:1.1em"><em><b>(' . $nota['dados']['nota_resumo'] . ')</b></em></p><hr>';
                                    //echo '<p class="card-text mb-0"  style="font-size:0.9em">por: ' . $nota['dados']['usuario_nome'] . '</p><hr>';
                                    echo '<p class="card-text mb-3" style="font-size:1.1em">' . $nota['dados']['nota_texto'] . '</p>';

                                    echo '<p class="card-text mb-0">
                                                <a type="button" href="nota_tecnica.php?proposicao=' . $proposicao['proposicao_id'] . '" class="btn btn-outline-success btn-sm" style="font-size:0.9em"><i class="fa-regular fa-pen-to-square"></i> Editar nota</a>
                                                <a type="button" href="imprimir_nota.php?proposicao=' . $proposicao['proposicao_id'] . '&imprimir=1" target="_blank" class="btn btn-outline-success btn-sm" style="font-size:0.9em"><i class="fa-solid fa-print"></i> Imprimir nota</a>
                                            </p>';
                                }



                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-header bg-secondary text-white px-2 py-1" style="font-size:0.950em"><i class="fa-solid fa-arrow-right"></i> Tramitações</div>
                            <div class="card-body p-2">
                                <?php

                                $itensPorPagina = 10;
                                $inicio = ($paginaTramitacaoAtual - 1) * $itensPorPagina;
                                $tabela = [];

                                $tramitacoes = getJson('https://dadosabertos.camara.leg.br/api/v2/proposicoes/' . $proposicaoId . '/tramitacoes?dataInicio=' . date('Y-m-d', strtotime($proposicao['proposicao_apresentacao'])) . '&dataFim=' . date('Y-m-d'));

                                usort($tramitacoes['dados'], function ($a, $b) {
                                    return strtotime($b['dataHora']) - strtotime($a['dataHora']);
                                });

                                $totalTramitacoes = count($tramitacoes['dados']);

                                $totalPaginas = ceil($totalTramitacoes / $itensPorPagina);

                                $tramitacoesPagina = array_slice($tramitacoes['dados'], $inicio, $itensPorPagina); ?>


                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered mb-0 custom_table">
                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Órgao</th>
                                                <th>Tramitação</th>
                                                <th>Despacho</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($tramitacoesPagina as $tramitacao) {
                                                echo '<tr>';
                                                echo '<td>' . date('d/m/Y', strtotime($tramitacao['dataHora'])) . '</td>';
                                                echo '<td>' . $tramitacao['siglaOrgao'] . '</td>';
                                                echo '<td>' . ($tramitacao['codTipoTramitacao'] == 322 && !empty($tramitacao['url']) ? '<a href="' . $tramitacao['url'] . '" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> ' . $tramitacao['descricaoTramitacao'] . '</a>' : $tramitacao['descricaoTramitacao']) . '</td>';
                                                echo '<td>' . ($tramitacao['codTipoTramitacao'] == 320 ? '<b><i class="fa-solid fa-user-tie"></i> ' . $tramitacao['despacho'] . '</b>' : $tramitacao['despacho']) . '</td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>


                                <?php


                                if ($totalPaginas > 1) { // Verifica se há mais de uma página
                                    echo '<nav aria-label="Page navigation">';
                                    echo '<ul class="pagination custom-pagination mt-2 mb-0">';

                                    for ($i = 1; $i <= $totalPaginas; $i++) {
                                        if ($i == $paginaTramitacaoAtual) {
                                            echo '<li class="page-item active" aria-current="page"><span class="page-link bg-success text-white">' . $i . '</span></li>';
                                        } else {
                                            echo '<li class="page-item"><a class="page-link" href="?proposicao=' . $proposicaoId . '&paginaTramitacao=' . $i . '">' . $i . '</a></li>';
                                        }
                                    }

                                    echo '</ul>';
                                    echo '</nav>';
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                </div>





            </div>
        </div>
    </div>
</body>

</html>