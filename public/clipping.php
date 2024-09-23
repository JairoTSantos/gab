<?php

require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';
require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/ClippingController.php';
$clippingController = new ClippingController();

require_once dirname(__DIR__) . '/app/controllers/TipoClippingController.php';
$clippingTipoController = new ClippingTipoController();

require_once dirname(__DIR__) . '/app/controllers/OrgaoController.php';
$orgaoController = new OrgaoController();

$itens = isset($_GET['itens']) ? (int)$_GET['itens'] : 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$ordenarPor = isset($_GET['ordenarPor']) ? htmlspecialchars($_GET['ordenarPor']) : 'clipping_criado_por';
$ordem = isset($_GET['ordem']) ? strtolower(htmlspecialchars($_GET['ordem'])) : 'asc';
$termo = isset($_GET['termo']) ? htmlspecialchars($_GET['termo']) : null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Clippings') ?>
</head>

<body class="bg-secondary">
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar() ?>
                <?php $layoutClass->cardDescription('<i class="fa-solid fa-plus"></i> Adicionar Clipping', '<p class="card-text mb-2">Seção para gerenciamento de clippings.  <p class="card-text mb-2"> Arquivos permitidos: PDF, JPG, PNG</p><p class="card-text mb-0">Todos os campos são obrigatórios</p>') ?>
                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2 ">
                            <div class="card-body p-0">
                                <nav class="navbar navbar-expand bg-body-tertiary p-0 ">
                                    <div class="container-fluid p-0">
                                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                            <ul class="navbar-nav me-auto mb-0 mb-lg-0">
                                                <li class="nav-item">
                                                    <a class="nav-link active p-1" aria-current="page" href="#">
                                                        <button class="btn btn-primary btn-sm" style="font-size: 0.850em;" id="btn_novo_tipo" type="button">
                                                            <i class="fa-solid fa-circle-plus"></i> Novo tipo
                                                        </button>
                                                        <button class="btn btn-secondary btn-sm" style="font-size: 0.850em;" id="btn_novo_orgao" type="button">
                                                            <i class="fa-solid fa-circle-plus"></i> Novo órgão
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
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <?php
                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {
                                    $clipping = [
                                        'clipping_resumo' => $_POST['clipping_resumo'],
                                        'clipping_link' => $_POST['clipping_link'],
                                        'clipping_orgao' => $_POST['clipping_orgao'],
                                        'arquivo' => $_FILES['clipping_arquivo'],
                                        'clipping_tipo' => $_POST['clipping_tipo'],
                                        'clipping_criado_por' => $_SESSION['usuario_id']
                                    ];

                                    $resultado = $clippingController->NovoClipping($clipping);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                    } else if ($resultado['status'] === 'duplicated') {
                                        $layoutClass->alert('info', $resultado['message'], 3);
                                    } else {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }
                                ?>
                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">

                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="clipping_link" placeholder="Link" required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <select class="form-select form-select-sm" name="clipping_orgao" id="clipping_orgao" required>
                                            <option value="1000">Órgão não informado</option>
                                            <?php
                                            $buscaOrgaos = $orgaoController->ListarOrgaos(1000);
                                            if ($buscaOrgaos['status'] == 'success') {
                                                foreach ($buscaOrgaos['dados'] as $orgaos) {
                                                    if ($orgaos['orgao_id'] == 1000) {
                                                        echo '<option value="' . $orgaos['orgao_id'] . '" selected>' . $orgaos['orgao_nome'] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $orgaos['orgao_id'] . '">' . $orgaos['orgao_nome'] . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                            <option value="+">Novo órgão + </option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <select class="form-select form-select-sm" name="clipping_tipo" id="clipping_tipo" required>
                                            <?php
                                            $buscaTipos = $clippingTipoController->ListarClippingTipos();
                                            if ($buscaTipos['status'] == 'success') {
                                                foreach ($buscaTipos['dados'] as $tipos) {
                                                    if ($tipos['clipping_tipo_id'] == 1000) {
                                                        echo '<option value="' . $tipos['clipping_tipo_id'] . '" selected>' . $tipos['clipping_tipo_nome'] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $tipos['clipping_tipo_id'] . '">' . $tipos['clipping_tipo_nome'] . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                            <option value="+">Novo tipo + </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="file" class="form-control form-control-sm" name="clipping_arquivo" placeholder="Arquivo">
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <textarea class="form-control form-control-sm" name="clipping_resumo" rows="10" placeholder="Texto do clipping" required></textarea>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
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
                                <form class="row g-2 form_custom mb-0" method="GET" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" name="ordenarPor" required>
                                            <option value="clipping_tipo" <?php echo $ordenarPor == 'clipping_tipo' ? 'selected' : ''; ?>>Ordenar por | Tipo</option>
                                            <option value="clipping_criado_em" <?php echo $ordenarPor == 'clipping_criado_em' ? 'clipping_criado_em' : ''; ?>>Ordenar por | Estado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" name="ordem" required>
                                            <option value="asc" <?php echo $ordem == 'asc' ? 'selected' : ''; ?>>Ordem Crescente</option>
                                            <option value="desc" <?php echo $ordem == 'desc' ? 'selected' : ''; ?>>Ordem Decrescente</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <select class="form-select form-select-sm" name="itens" required>
                                            <option value="5" <?php echo $itens == 5 ? 'selected' : ''; ?>>5 itens</option>
                                            <option value="10" <?php echo $itens == 10 ? 'selected' : ''; ?>>10 itens</option>
                                            <option value="25" <?php echo $itens == 25 ? 'selected' : ''; ?>>25 itens</option>
                                            <option value="50" <?php echo $itens == 50 ? 'selected' : ''; ?>>50 itens</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="termo" placeholder="Buscar...">
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
                $clippings = $clippingController->ListarClippings($itens, $pagina, $ordem, $ordenarPor, $termo);
                $tabela = [];

                if ($clippings['status'] == 'success' && $clippings['status'] != 'empty') {
                    foreach ($clippings['dados'] as $clipping) {
                        $tabela[] = [
                            'Resumo' => '<a href="editar-clipping.php?id=' . $clipping['clipping_id'] . '">' . $clipping['clipping_resumo'] . '</a>',
                            'Link' => $clipping['clipping_link'],
                            'Órgão' => $clipping['orgao_nome'],
                            'Tipo' => $clipping['clipping_tipo_nome'],
                            'Criado em | por' => date('d/m/Y', strtotime($clipping['clipping_criado_em'])) . ' | ' . $clipping['usuario_nome']
                        ];
                    }
                    echo $layoutClass->criarTabela($tabela);
                } else if ($clippings['status'] == 'error') {
                    echo $layoutClass->criarTabela([['Mensagem' => 'Erro interno do servidor.']]);
                } else {
                    echo $layoutClass->criarTabela([]);
                }
                ?>
                <ul class="pagination custom-pagination mb-0">
                    <?php
                    if (isset($clippings['total_paginas'])) {
                        $totalPagina = $clippings['total_paginas'];
                    } else {
                        $totalPagina = 0;
                    }

                    if ($totalPagina > 0 && $totalPagina != 1) {
                        echo '<li class="page-item"><a class="page-link" href="clipping.php?itens=' . $itens . '&pagina=1&ordenarPor=' . $ordenarPor . '&ordem=' . $ordem . (isset($termo) ? '&termo=' . $termo : '') . '">Primeira</a></li>';

                        for ($i = 1; $i < $totalPagina - 1; $i++) {
                            echo '<li class="page-item"><a class="page-link" href="clipping.php?itens=' . $itens . '&pagina=' . ($i + 1) . '&ordenarPor=' . $ordenarPor . '&ordem=' . $ordem . (isset($termo) ? '&termo=' . $termo : '') . '">' . ($i + 1) . '</a></li>';
                        }

                        echo '<li class="page-item"><a class="page-link" href="clipping.php?itens=' . $itens . '&pagina=' . $totalPagina . '&ordenarPor=' . $ordenarPor . '&ordem=' . $ordem . (isset($termo) ? '&termo=' . $termo : '') . '">Última</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <script>
        $('#btn_novo_tipo').click(function() {

            if (window.confirm("Você realmente deseja inserir um novo tipo?")) {
                window.location.href = "clipping-tipo.php";
            }

        });

        $('#btn_novo_orgao').click(function() {

            if (window.confirm("Você realmente deseja inserir um novo órgão?")) {
                window.location.href = "orgaos.php";
            }

        });


        $('#clipping_tipo').change(function() {
            if ($('#clipping_tipo').val() == '+') {
                if (window.confirm("Você realmente deseja inserir um novo tipo?")) {
                    window.location.href = "clipping-tipo.php";
                }
            }
        });

        $('#clipping_orgao').change(function() {
            if ($('#clipping_orgao').val() == '+') {
                if (window.confirm("Você realmente deseja inserir um novo órgão?")) {
                    window.location.href = "orgaos.php";
                }
            }
        });
    </script>
</body>

</html>