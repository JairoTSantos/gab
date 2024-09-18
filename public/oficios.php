<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/OficioController.php';
$oficioController = new OficioController();

$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : date('Y');
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Ofícios') ?>
</head>


<body class="bg-secondary">
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar() ?>
                <?php $layoutClass->cardDescription('<i class="fa-regular fa-file-lines"></i> Arquivar ofícios', '<p class="card-text mb-2">Seção para arquivamento de ofícios. <p class="card-text mb-2">Todos os campos são obrigatórios</p><p class="card-text mb-0">O arquivo deve ser em DOCX, DOC ou PDF e ter até 5mb</p>') ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <?php

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {
                                    $usuario = [
                                        'oficio_titulo' => $_POST['oficio_titulo'],
                                        'oficio_ano' => $_POST['oficio_ano'],
                                        'oficio_resumo' => $_POST['oficio_resumo'],
                                        'arquivo' => $_FILES['arquivo']
                                    ];
                                    $resultado = $oficioController->NovoOficio($usuario);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                    } else if ($resultado['status'] === 'file_not_permitted' || $resultado['status'] === 'duplicated' || $resultado['status'] === 'file_too_large') {
                                        $layoutClass->alert('info', $resultado['message'], 0);
                                    } else if ($resultado['status'] === 'error' || $resultado['status'] === 'forbidden') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }

                                ?>
                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
                                    <div class="col-md-4 col-12">
                                        <input type="text" class="form-control form-control-sm" name="oficio_titulo" placeholder="Titulo" required>
                                    </div>
                                    <div class="col-md-1 col-12">
                                    <select class="form-select form-select-sm" name="oficio_ano" required>
                                            <?php
                                            for ($i = 1999; $i < (date('Y') + 1); $i++) {
                                                if ($i == date('Y')) {
                                                    echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                                } else {
                                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-7 col-12">
                                        <input type="file" class="form-control form-control-sm" name="arquivo" required>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <textarea class="form-control form-control-sm" name="oficio_resumo" rows="5" placeholder="Resumo do ofício"></textarea>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="file-upload">
                                            <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
                                        </div>
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
                                        <select class="form-select form-select-sm" name="ano" required>
                                            <?php
                                            for ($i = 1999; $i < (date('Y') + 1); $i++) {
                                                if ($i == $ano) {
                                                    echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                                } else {
                                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <input type="text" class="form-control form-control-sm" name="busca" placeholder="Buscar..." value="<?php echo $busca ?>">
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
                $oficios = $oficioController->ListarOficios($ano, $busca);
                $tabela = [];

                if ($oficios['status'] == 'success' && $oficios['status'] != 'empty') {
                    foreach ($oficios['dados'] as $oficio) {
                        $tabela[] = [
                            'Titulo' => '<a href="editar-oficio.php?id=' . $oficio['oficio_id'] . '">' . $oficio['oficio_titulo'] . '</a>',
                            'Ano' => $oficio['oficio_ano'],
                            'Resumo' => $oficio['oficio_resumo'],
                            'Criado em' => date('d/m - H:i', strtotime($oficio['oficio_criado_em'])),
                        ];
                    }
                    echo $layoutClass->criarTabela($tabela);
                } else if ($oficios['status'] == 'error') {
                    echo $layoutClass->criarTabela([['Mensagem' => 'Erro interno do servidor.']]);
                } else {
                    echo $layoutClass->criarTabela([]);
                }

                ?>

            </div>
        </div>
    </div>
</body>

</html>