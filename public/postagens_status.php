<?php

require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/StatusPostagensController.php';
$statusPostagemController = new StatusPostagemController();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Status de Postagens') ?>
</head>

<body class="bg-secondary">
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar() ?>
                <?php $layoutClass->cardDescription('<i class="fa-solid fa-user-plus"></i> Adicionar status de postagens', '<p class="card-text mb-0">Seção para gerenciamento de status de postagens</p>') ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <?php

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {
                                    $statusPostagem = [
                                        'postagem_status_nome' => $_POST['nome'],
                                        'postagem_status_descricao' => $_POST['descricao'],
                                    ];

                                    $resultado = $statusPostagemController->NovoStatusPostagem($statusPostagem);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                    } else if ($resultado['status'] === 'duplicated') {
                                        $layoutClass->alert('info', $resultado['message'], 3);
                                    } else if ($resultado['status'] === 'error' || $resultado['status'] === 'bad_request') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }

                                ?>
                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-12 col-12">
                                        <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome do status" required>
                                    </div>

                                    <div class="col-md-12 col-12">
                                        <textarea class="form-control form-control-sm" name="descricao" rows="5" placeholder="Descrição do status de postagem"></textarea>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $statusPostagens = $statusPostagemController->ListarStatusPostagens();
                $tabela = [];

                if ($statusPostagens['status'] == 'success' && $statusPostagens['status'] != 'empty') {
                    foreach ($statusPostagens['dados'] as $status) {
                        $tabela[] = [
                            'Nome' => $status['postagem_status_nome'],
                            'Descrição' => $status['postagem_status_descricao'],
                            'Criado em | por' => date('d/m', strtotime($status['postagem_status_criado_em'])) . ' | ' . $status['usuario_nome'],
                        ];
                    }
                    echo $layoutClass->criarTabela($tabela);
                } else if ($statusPostagens['status'] == 'error') {
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