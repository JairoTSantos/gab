<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/app/controllers/EleicoesController.php';
$eleicoesController = new EleicoesController();

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

$ano = filter_input(INPUT_GET, 'ano', FILTER_SANITIZE_NUMBER_INT) ?: 2022;

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Eleições Gerais'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar() ?>
                <?php $layoutClass->cardDescription('<i class="fa-solid fa-user-plus"></i> Resultado das eleições', '<p class="card-text mb-2">Seção para conferência dos resultados das eleições.</p><p class="card-text mb-2">Aqui você poderá verificar os resultados por município.</p><p class="card-text mb-0">Os dados apresentados são provenientes do Tribunal Superior Eleitoral (TSE) e estão sujeitos a revisão e conferência final.</p>') ?>
                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <form class="row g-2 form_custom mb-0" method="GET"
                                    enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-2 col-10">
                                        <select class="form-select form-select-sm" name="ano" required>
                                            <option value="" disabled selected>Selecione um ano</option>
                                            <option value="1998" <?php echo $ano == '1998' ? 'selected' : ''; ?>>1998 -
                                                Eleições Gerais</option>
                                            <option value="2000" <?php echo $ano == '2000' ? 'selected' : ''; ?>>2000 -
                                                Eleições Municipais</option>
                                            <option value="2002" <?php echo $ano == '2002' ? 'selected' : ''; ?>>2002 -
                                                Eleições Gerais</option>
                                            <option value="2004" <?php echo $ano == '2004' ? 'selected' : ''; ?>>2004 -
                                                Eleições Municipais</option>
                                            <option value="2006" <?php echo $ano == '2006' ? 'selected' : ''; ?>>2006 -
                                                Eleições Gerais</option>
                                            <option value="2008" <?php echo $ano == '2008' ? 'selected' : ''; ?>>2008 -
                                                Eleições Municipais</option>
                                            <option value="2010" <?php echo $ano == '2010' ? 'selected' : ''; ?>>2010 -
                                                Eleições Gerais</option>
                                            <option value="2012" <?php echo $ano == '2012' ? 'selected' : ''; ?>>2012 -
                                                Eleições Municipais</option>
                                            <option value="2014" <?php echo $ano == '2014' ? 'selected' : ''; ?>>2014 -
                                                Eleições Gerais</option>
                                            <option value="2016" <?php echo $ano == '2016' ? 'selected' : ''; ?>>2016 -
                                                Eleições Municipais</option>
                                            <option value="2018" <?php echo $ano == '2018' ? 'selected' : ''; ?>>2018 -
                                                Eleições Gerais</option>
                                            <option value="2020" <?php echo $ano == '2020' ? 'selected' : ''; ?>>2020 -
                                                Eleições Municipais</option>
                                            <option value="2022" <?php echo $ano == '2022' ? 'selected' : ''; ?>>2022 -
                                                Eleições Gerais</option>
                                            <option value="2024" <?php echo $ano == '2024' ? 'selected' : ''; ?>>2024 -
                                                Eleições Municipais</option>
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

                $resultado = $eleicoesController->buscarPorMunicipio($ano);
                $tabela = [];
                $totalVotos = 0;

                if ($resultado['status'] == 'success') {
                    foreach ($resultado['dados'] as $municipio) {
                        $totalVotos += $municipio['municipio_votos'];
                    }

                    foreach ($resultado['dados'] as $municipio) {
                        $porcentagem = ($municipio['municipio_votos'] / $totalVotos) * 100;

                        $porcentagemFormatada = number_format($porcentagem, 2);

                        $barraProgresso = '<div class="progress" style="width: 100%; background-color: #f3f3f3;">
                                                <div class="progress-bar" style="width: ' . $porcentagemFormatada . '%; background-color: #4caf50;"></div>
                                           </div>';

                        $tabela[] = [
                            'Muncípio' => $municipio['municipio_nome'],
                            'Total de votos (%)' => number_format($municipio['municipio_votos'], 0, ',', '.') . ' (' . $porcentagemFormatada . '%)',
                            'Gráfico' => $barraProgresso
                        ];
                    }

                }

                ?>

                <?php
                echo $layoutClass->criarTabela($tabela);
                ?>
                <div class="card mb-2">
                    <div class="card-body p-2">
                        <p class="mb-0">Total de votos em <?php echo $ano ?>:
                            <b><?php echo number_format($totalVotos, 0, ',', '.') ?></b>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>