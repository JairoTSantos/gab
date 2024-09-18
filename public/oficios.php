<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/OficioController.php';
$oficioController = new OficioController();

require_once dirname(__DIR__) . '/app/controllers/OrgaoController.php';
$orgaoController = new OrgaoController();

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

                                                        <button class="btn btn-secondary btn-sm" style="font-size: 0.850em;" id="btn_novo_orgao" type="button">
                                                            <i class="fa-solid fa-circle-plus"></i> Novo órgão
                                                        </button>
                                                        <!--<button class="btn btn-secondary btn-sm" style="font-size: 0.850em;" id="btn_imprimir" type="button">
                                                            <i class="fa-solid fa-print"></i> Imprimir
                                                        </button>-->
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
                                    $usuario = [
                                        'oficio_titulo' => $_POST['oficio_titulo'],
                                        'oficio_ano' => $_POST['oficio_ano'],
                                        'oficio_resumo' => $_POST['oficio_resumo'],
                                        'oficio_orgao' => $_POST['oficio_orgao'],
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
                                    <div class="col-md-2 col-12">
                                        <select class="form-select form-select-sm" name="oficio_orgao" id="orgao" required>
                                            <option value="1000">Órgão não informado</option>
                                            <?php
                                            $orgaos = $orgaoController->ListarOrgaos(1000);
                                            if ($orgaos['status'] == 'success') {
                                                foreach ($orgaos['dados'] as $orgao) {
                                                    echo '<option value="' . $orgao['orgao_id'] . '">' . $orgao['orgao_nome'] . '</option>';
                                                }
                                            }
                                            ?>
                                            <option value="+">Novo órgão + </option>
                                        </select>
                                    </div>
                                    <div class="col-md-5 col-12">
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
                            'Órgão' => $oficio['orgao_nome'],
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
    <script>
        $('#orgao').change(function() {
            if ($('#orgao').val() == '+') {
                if (window.confirm("Você realmente deseja inserir um novo órgão?")) {
                    window.location.href = "orgaos.php";
                }
            }
        });

        $('#btn_novo_orgao').click(function() {

            if (window.confirm("Você realmente deseja inserir um novo órgão?")) {
                window.location.href = "orgaos.php";
            }

        });

       
    </script>
</body>

</html>