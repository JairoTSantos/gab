<?php

require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';
require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/PostagemController.php';
$postagemController = new PostagemController();

require_once dirname(__DIR__) . '/app/controllers/StatusPostagensController.php';
$statusPostagensController = new StatusPostagemController();

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
                <?php $layoutClass->cardDescription('<i class="fa-solid fa-plus"></i> Adicionar Postagem', '<p class="card-text mb-2">Seção para gerenciamento de postagens. <p class="card-text mb-0">Todos os campos são obrigatórios</p>') ?>
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

                                    $resultado = $postagemController->NovaPostagem($postagem);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                    } else if ($resultado['status'] === 'file_not_permitted' || $resultado['status'] === 'file_too_large') {
                                        $layoutClass->alert('info', $resultado['message'], 3);
                                    } else if ($resultado['status'] === 'error' || $resultado['status'] === 'forbidden') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }
                                ?>
                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="postagem_titulo" placeholder="Título" required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="postagem_midias" placeholder="Mídias (facebook, instagram, site...)" required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="date" class="form-control form-control-sm" name="postagem_data" value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <select class="form-select form-select-sm" name="postagem_status" required>
                                            <?php
                                            $status_postagens = $statusPostagensController->ListarStatusPostagens();

                                            if ($status_postagens['status'] == 'success') {
                                                foreach ($status_postagens['dados'] as $status) {
                                                    if ($status['postagem_status_id'] == 1000) {
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
                                        <textarea class="form-control form-control-sm" name="postagem_informacoes" placeholder="Informações" rows="4" required></textarea>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $postagens = $postagemController->ListarPostagens();
                $tabela = [];

                if ($postagens['status'] == 'success' && $postagens['status'] != 'empty') {
                    foreach ($postagens['dados'] as $postagem) {
                        $tabela[] = [
                            'Título' => '<a href="editar-postagem.php?id=' . $postagem['postagem_id'] . '">' . $postagem['postagem_titulo'] . '</a>',
                            'Data da publicação' => date('d/m/Y', strtotime($postagem['postagem_data'])),
                            'Status' => '<b><i class="fa-solid fa-circle-info"></i> ' . $postagem['postagem_status_nome'] . '</b>',
                            'Informações' => $postagem['postagem_informacoes'],
                            'Mídias' => $postagem['postagem_midias'],
                           
                            'Criado por' => $postagem['usuario_nome'],
                            'Criado em' => date('d/m - H:i', strtotime($postagem['postagem_criada_em'])),
                        ];
                    }
                    echo $layoutClass->criarTabela($tabela);
                } else if ($postagens['status'] == 'error') {
                    echo $layoutClass->criarTabela([['Mensagem' => 'Erro interno do servidor.']]);
                } else {
                    echo $layoutClass->criarTabela([]);
                }
                ?>
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
    </script>

</body>

</html>