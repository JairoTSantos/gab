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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$buscaClipping = $clippingController->BuscarClipping('clipping_id', $id);

if ($buscaClipping['status'] == 'empty' || $buscaClipping['status'] == 'error') {
    header('Location: clipping.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Editar clipping') ?>
</head>

<body class="bg-secondary">
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar(true, 'clipping.php') ?>
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
                                        'clipping_tipo' => $_POST['clipping_tipo']
                                    ];

                                    $resultado = $clippingController->AtualizarClipping($id, $clipping);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                        echo '<script>
                                        setTimeout(function(){
                                            window.location.href = "editar-clipping.php?id=' . $id . '";
                                        }, 1000);
                                    </script>';
                                    } else if ($resultado['status'] === 'duplicated') {
                                        $layoutClass->alert('info', $resultado['message'], 3);
                                    } else {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                                    $resultado = $clippingController->ApagarClipping($id);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                        echo '<script>
                                                    setTimeout(function(){
                                                        window.location.href = "clipping.php";
                                                    }, 500);
                                                </script>';
                                    } elseif ($resultado['status'] === 'error' || $resultado['status'] === 'invalid_id' || $resultado['status'] === 'delete_conflict') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }

                                ?>
                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">

                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="clipping_link" placeholder="Link" value="<?php echo $buscaClipping['dados']['clipping_link'] ?>" required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <select class="form-select form-select-sm" name="clipping_orgao" id="clipping_orgao" required>
                                            <option value="1000">Órgão não informado</option>
                                            <?php
                                            $buscaOrgaos = $orgaoController->ListarOrgaos(1000);
                                            if ($buscaOrgaos['status'] == 'success') {
                                                foreach ($buscaOrgaos['dados'] as $orgaos) {
                                                    if ($orgaos['orgao_id'] == $buscaClipping['dados']['clipping_orgao']) {
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
                                                    if ($tipos['clipping_tipo_id'] == $buscaClipping['dados']['clipping_tipo']) {
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
                                        <textarea class="form-control form-control-sm" name="clipping_resumo" rows="10" placeholder="Texto do clipping" required><?php echo $buscaClipping['dados']['clipping_resumo'] ?></textarea>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>

                                        <button type="submit" class="btn btn-danger btn-sm" name="btn_apagar"><i class="fa-solid fa-trash"></i> Apagar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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

        $('button[name="btn_apagar"]').on('click', function(event) {
            const confirmacao = confirm("Tem certeza que deseja apagar esta pessoa?");
            if (!confirmacao) {
                event.preventDefault();
            }
        });
    </script>
</body>

</html>