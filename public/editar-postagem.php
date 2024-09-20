<?php

require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/app/core/UploadFile.php';
$upload = new UploadFile();

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/PostagemController.php';
$postagemController = new PostagemController();

require_once dirname(__DIR__) . '/app/controllers/StatusPostagensController.php';
$statusPostagensController = new StatusPostagemController();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$busca_postagem = $postagemController->BuscarPostagem('postagem_id', $id);

$pasta = '../public/arquivos/postagens/' . $busca_postagem['dados']['postagem_pasta'];

if ($busca_postagem['status'] == 'empty' || $busca_postagem['status'] == 'error') {
    header('Location: postagens.php');
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Postagens') ?>
</head>

<body class="bg-secondary">
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar() ?>
                <?php $layoutClass->cardDescription('<i class="fa-solid fa-plus"></i> Gerenciar Postagem', '<p class="card-text mb-2">Pasta para arquivamento da postagem. <p class="card-text mb-0">Salve os arquivos das postagens para arquivar.</p>') ?>
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
                                                        <button class="btn btn-primary btn-sm" style="font-size: 0.850em;" id="btn_novo_status" type="button">
                                                            <i class="fa-solid fa-circle-plus"></i> Novo status
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
                                    $postagem = [
                                        'postagem_titulo' => $_POST['postagem_titulo'],
                                        'postagem_data' => $_POST['postagem_data'],
                                        'postagem_informacoes' => $_POST['postagem_informacoes'],
                                        'postagem_status' => $_POST['postagem_status'],
                                        'postagem_midias' => $_POST['postagem_midias']
                                    ];

                                    $resultado = $postagemController->AtualizarPostagem($id, $postagem);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                        echo '<script>
                                        setTimeout(function(){
                                            window.location.href = "editar-postagem.php?id=' . $id . '";
                                        }, 1000);
                                    </script>';
                                    } else if ($resultado['status'] === 'file_not_permitted' || $resultado['status'] === 'file_too_large') {
                                        $layoutClass->alert('info', $resultado['message'], 3);
                                    } else if ($resultado['status'] === 'error' || $resultado['status'] === 'forbidden') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                                    $resultado = $postagemController->ApagarPostagem($id, $busca_postagem['dados']['postagem_pasta']);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                        echo '<script>
                                                    setTimeout(function(){
                                                        window.location.href = "postagens.php";
                                                    }, 500);
                                                </script>';
                                    } elseif ($resultado['status'] === 'error' || $resultado['status'] === 'invalid_id' || $resultado['status'] === 'delete_conflict') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }

                                ?>
                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="postagem_titulo" placeholder="Título" value="<?php echo $busca_postagem['dados']['postagem_titulo'] ?>" required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="postagem_midias" placeholder="Mídias (facebook, instagram, site...)" value="<?php echo $busca_postagem['dados']['postagem_midias'] ?>" required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="date" class="form-control form-control-sm" name="postagem_data" value="<?php echo date('Y-m-d', strtotime($busca_postagem['dados']['postagem_data'])) ?>" required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <select class="form-select form-select-sm" name="postagem_status" required>
                                            <?php
                                            $status_postagens = $statusPostagensController->ListarStatusPostagens();

                                            if ($status_postagens['status'] == 'success') {
                                                foreach ($status_postagens['dados'] as $status) {
                                                    if ($status['postagem_status_id'] == $busca_postagem['dados']['postagem_status']) {
                                                        echo '<option value="' . $status['postagem_status_id'] . '" selected>' . $status['postagem_status_nome'] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $status['postagem_status_id'] . '">' . $status['postagem_status_nome'] . '</option>';
                                                    }
                                                }
                                            }

                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <textarea class="form-control form-control-sm" name="postagem_informacoes" placeholder="Informações" rows="4" required><?php echo $busca_postagem['dados']['postagem_informacoes'] ?></textarea>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
                                        <button type="submit" class="btn btn-danger btn-sm" name="btn_apagar"><i class="fa-solid fa-trash"></i> Apagar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2 ">
                            <div class="card-body p-1">
                                <?php

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_upload'])) {
                                    $uploadResult = $upload->salvarArquivo($pasta, $_FILES['foto']);


                                    if ($uploadResult['status'] == 'upload_ok') {
                                        $layoutClass->alert('success', 'Arquivo salvo com sucesso', 3);
                                    } else if ($uploadResult['status'] == 'file_not_permitted' || $uploadResult['status'] == 'file_too_large') {
                                        $layoutClass->alert('info', 'Tipo de arquivo não permitido ou muito grande', 3);
                                    } else if ($uploadResult['status'] == 'error' || $uploadResult['status'] == 'forbidden') {
                                        $layoutClass->alert('danger', 'Erro interno do servidor.', 3);
                                    }
                                }



                                ?>


                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
                                    <div class="col-md-3 col-12">
                                        <input type="file" class="form-control form-control-sm" name="foto" required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <button type="submit" class="btn btn-primary btn-sm" name="btn_upload"><i class="fa-regular fa-floppy-disk"></i> Salvar Arquivo</button>

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


                                if (is_dir($pasta)) {


                                    $arquivos = scandir($pasta);
                                    $arquivos = array_diff($arquivos, array('.', '..'));


                                    if (!empty($arquivos)) {


                                        if (isset($_POST['arquivo_para_apagar'])  && isset($_POST['btn_apagar_arquivo'])) {
                                            $arquivoParaApagar = $_POST['arquivo_para_apagar'];
                                            $caminhoArquivo = $pasta . '/' . $arquivoParaApagar;

                                            if (file_exists($caminhoArquivo)) {
                                                unlink($caminhoArquivo);
                                                echo '<script>
                                                setTimeout(function(){
                                                    window.location.href = "editar-postagem.php?id=' . $id . '";
                                                }, 1);
                                            </script>';
                                            } else {
                                                echo "<p>O arquivo não existe.</p>";
                                            }
                                        }

                                        echo '<div class="d-flex flex-wrap gap-3">';
                                        foreach ($arquivos as $arquivo) {

                                            $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));

                                            
                                            if (in_array($extensao, ['jpg', 'jpeg', 'png'])) {                                                
                                                $arquivo_para_exibir = $pasta . '/' . $arquivo;
                                                $exibir_midia = '<img class="img-thumbnail" src="' . $arquivo_para_exibir . '" style="width: 100%; height: auto; object-fit: contain;" alt="Imagem" />';
                                            } elseif ($extensao === 'zip') {
                                                $arquivo_para_exibir = '../public/img/zip.jpg';
                                                $exibir_midia = '<img class="img-thumbnail" src="' . $arquivo_para_exibir . '" style="width: 100%; height: auto; object-fit: contain;" alt="Imagem" />';
                                            } elseif (in_array($extensao, ['mp4', 'mov'])) {
                                                $arquivo_para_exibir = $pasta . '/' . $arquivo;
                                                $exibir_midia = '<video controls style="width: 100%; height: auto;">
                                                                    <source src="' . $arquivo_para_exibir . '" type="video/' . $extensao . '">
                                                                    Seu navegador não suporta o elemento de vídeo.
                                                                 </video>';
                                            } else if ($extensao === 'psd') {
                                                $arquivo_para_exibir = '../public/img/psd.png';
                                                $exibir_midia = '<img class="img-thumbnail" src="' . $arquivo_para_exibir . '" style="width: 100%; height: auto; object-fit: contain;" alt="Imagem" />';
                                            } else if ($extensao === 'ai') {
                                                $arquivo_para_exibir = '../public/img/ai.png';
                                                $exibir_midia = '<img class="img-thumbnail" src="' . $arquivo_para_exibir . '" style="width: 100%; height: auto; object-fit: contain;" alt="Imagem" />';
                                            }

                                            // Exibe o HTML gerado
                                            echo '<div style="width: 150px; height: auto;">';
                                            echo '<a href="' . $pasta . '/' . $arquivo . '" target="_blank">';
                                            echo $exibir_midia;
                                            echo '</a>';
                                            echo '<form method="POST">';
                                            echo '<input type="hidden" name="arquivo_para_apagar" value="' . $arquivo . '">';
                                            echo '<button type="submit" class="btn btn-danger btn-sm mt-2" name="btn_apagar_arquivo">Apagar</button>';
                                            echo '</form>';
                                            echo '</div>';
                                        }


                                        echo '</div>';
                                    } else {
                                        echo '<div style="width: 150px; height: auto;">';
                                        echo '<img class="img-thumbnail" src="../public/img/empty-folder.svg" style="width: 100%; height: auto; object-fit: contain;" alt="Imagem" />';
                                        echo '</div>';
                                    }
                                } 
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $('#btn_novo_status').click(function() {
                    if (window.confirm("Você realmente deseja inserir um novo status?")) {
                        window.location.href = "postagens_status.php";
                    } else {
                        return false;
                    }
                });

                $('button[name="btn_apagar"]').on('click', function(event) {
                    const confirmacao = confirm("Tem certeza que deseja apagar esta postage,?");
                    if (!confirmacao) {
                        event.preventDefault();
                    }
                });

                $('button[name="btn_apagar_arquivo"]').on('click', function(event) {
                    const confirmacao = confirm("Tem certeza que deseja apagar este arquivo?");
                    if (!confirmacao) {
                        event.preventDefault();
                    }
                });
            </script>

</body>

</html>