<?php

require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/ProfissaoController.php';
$profissaoController = new ProfissaoController();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Profissões') ?>
</head>

<body class="bg-secondary">
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar() ?>
                <?php $layoutClass->cardDescription('<i class="fa-solid fa-briefcase"></i> Adicionar Nova Profissão', '<p class="card-text mb-0">Seção para gerenciamento de profissões</p>') ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <?php

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {
                                    $profissao = [
                                        'pessoas_profissoes_nome' => $_POST['nome'],
                                        'pessoas_profissoes_descricao' => $_POST['descricao'],
                                        'pessoas_profissoes_criado_por' => $_SESSION['usuario_id'] // Assumindo que o ID do usuário está na sessão
                                    ];

                                    $resultado = $profissaoController->NovaProfissao($profissao);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', 'Profissão adicionada com sucesso!', 3);
                                    } else if ($resultado['status'] === 'duplicated') {
                                        $layoutClass->alert('info', 'Essa profissão já existe.', 3);
                                    } else if ($resultado['status'] === 'error' || $resultado['status'] === 'bad_request') {
                                        $layoutClass->alert('danger', 'Erro ao adicionar a profissão.', 3);
                                    }
                                }

                                ?>
                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-5 col-12">
                                        <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome da Profissão" required>
                                    </div>

                                    <div class="col-md-12 col-12">
                                        <textarea class="form-control form-control-sm" name="descricao" rows="5" placeholder="Descrição da profissão"></textarea>
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
                $profissoes = $profissaoController->ListarProfissoes();
                $tabela = [];

                if ($profissoes['status'] == 'success' && $profissoes['status'] != 'empty') {
                    foreach ($profissoes['dados'] as $profissao) {
                        $tabela[] = [
                            'Nome' => $profissao['pessoas_profissoes_nome'],
                            'Descrição' => $profissao['pessoas_profissoes_descricao'],
                            'Criado em | por' => date('d/m', strtotime($profissao['pessoas_profissoes_criado_em'])) . ' | ' . $profissao['usuario_nome'],
                        ];
                    }
                    echo $layoutClass->criarTabela($tabela);
                } else if ($profissoes['status'] == 'error') {
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
