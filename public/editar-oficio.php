<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/OficioController.php';
$oficioController = new OficioController();

require_once dirname(__DIR__) . '/app/controllers/OrgaoController.php';
$orgaoController = new OrgaoController();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$buscarOficio = $oficioController->BuscarOfício('oficio_id', $id);

if ($buscarOficio['status'] == 'empty' || $buscarOficio['status'] == 'error') {
    header('Location: oficios.php');
}


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
                <?php $layoutClass->navBar(true, 'oficios.php') ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm mb-2 card-background">
                            <div class="card-body p-2">
                                <div class="row">
                                    <div class="col-12 col-md-11 mt-2 ">
                                        <h5 class="card-title"><?php echo $buscarOficio['dados']['oficio_titulo']; ?></h5>
                                    </div>
                                </div>
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
                                    $resultado = $oficioController->AtualizarOficio($usuario, $id);



                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                        echo '<script>
                                                    setTimeout(function(){
                                                        window.location.href = "editar-oficio.php?id=' . $id . '.php";
                                                    }, 500);
                                                </script>';
                                    } else if ($resultado['status'] === 'file_not_permitted' || $resultado['status'] === 'duplicated' || $resultado['status'] === 'file_too_large') {
                                        $layoutClass->alert('info', $resultado['message'], 0);
                                    } else if ($resultado['status'] === 'error' || $resultado['status'] === 'forbidden') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                                    $resultado = $oficioController->ApagarOficio($id);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                        echo '<script>
                                                    setTimeout(function(){
                                                        window.location.href = "oficios.php";
                                                    }, 500);
                                                </script>';
                                    } elseif ($resultado['status'] === 'error' || $resultado['status'] === 'invalid_id' || $resultado['status'] === 'delete_conflict') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }

                                ?>
                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
                                    <div class="col-md-4 col-12">
                                        <input type="text" class="form-control form-control-sm" name="oficio_titulo" placeholder="Titulo" value="<?php echo $buscarOficio['dados']['oficio_titulo'] ?>">
                                    </div>
                                    <div class="col-md-1 col-12">
                                        <select class="form-select form-select-sm" name="oficio_ano" required>
                                            <?php
                                            for ($i = 1999; $i < (date('Y') + 1); $i++) {
                                                if ($i == $buscarOficio['dados']['oficio_ano']) {
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
                                                    if ($orgao['orgao_id'] == $buscarOficio['dados']['oficio_orgao']) {
                                                        echo '<option value="' . $orgao['orgao_id'] . '" selected>' . $orgao['orgao_nome'] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $orgao['orgao_id'] . '">' . $orgao['orgao_nome'] . '</option>';
                                                    }
                                                }
                                            }
                                            ?>

                                            <option value="+">Novo órgão + </option>
                                        </select>
                                    </div>
                                    <div class="col-md-5 col-12">
                                        <input type="file" class="form-control form-control-sm" name="arquivo">
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <textarea class="form-control form-control-sm" name="oficio_resumo" rows="5" placeholder="Resumo do ofício"><?php echo $buscarOficio['dados']['oficio_resumo'] ?></textarea>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="file-upload">
                                            <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
                                            <button type="submit" class="btn btn-danger btn-sm" name="btn_apagar"><i class="fa-solid fa-trash"></i> Apagar</button>
                                        </div>
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
                                <?php
                                $arquivo = '..' . $buscarOficio['dados']['oficio_arquivo'];
                                $extensao = pathinfo($arquivo, PATHINFO_EXTENSION);

                                if ($extensao === 'pdf') {
                                    echo '<a class="btn btn-sm btn-primary mb-2" style="font-size:1em" href="' . htmlspecialchars($arquivo) . '" download><i class="fa-solid fa-download"></i> Baixar o arquivo PDF</a>';
                                    echo '<embed src="' . htmlspecialchars($arquivo) . '" type="application/pdf" width="100%" height="900px" />';
                                } elseif ($extensao === 'jpg' || $extensao === 'png') {
                                    echo '<img src="' . htmlspecialchars($arquivo) . '" alt="Imagem" style="max-width: 100%; height: auto;" />';
                                } elseif ($extensao === 'docx' || $extensao === 'doc') {
                                    echo '<a class="btn btn-sm btn-primary" style="font-size:1em" href="' . htmlspecialchars($arquivo) . '" download><i class="fa-solid fa-download"></i> Baixar o arquivo ' . $extensao . '</a>';
                                } else {
                                    echo 'Tipo de arquivo não suportado.';
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('button[name="btn_apagar"]').on('click', function(event) {
            const confirmacao = confirm("Tem certeza que deseja apagar este ofício?");
            if (!confirmacao) {
                event.preventDefault();
            }
        });



        $('button[name="btn_salvar"]').on('click', function(event) {
            const confirmacao = confirm("Tem certeza que deseja atualizar este órgao?");
            if (!confirmacao) {
                event.preventDefault();
            }
        });

        $('#btn_novo_orgao').click(function() {

            if (window.confirm("Você realmente deseja inserir um novo órgão?")) {
                window.location.href = "orgaos.php";
            }

        });

        $('#orgao').change(function() {
            if ($('#orgao').val() == '+') {
                if (window.confirm("Você realmente deseja inserir um novo órgão?")) {
                    window.location.href = "orgaos.php";
                }
            }
        });
    </script>
</body>

</html>