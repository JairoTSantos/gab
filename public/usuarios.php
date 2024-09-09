<?php
require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/UsuarioController.php';
$usuarioController = new UsuarioController();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Usuários') ?>
</head>


<body class="bg-secondary">
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar() ?>
                <?php $layoutClass->cardDescription('<i class="fa-solid fa-user-plus"></i> Adicionar usuários', '<p class="card-text mb-2">Seção para gerenciamento do sistema. <p class="card-text mb-2">Todos os campos são obrigatórios (exceto a foto)</p><p class="card-text mb-0">A foto deve ser em JPG ou PNG e ter até 5mb</p>') ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <?php

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {
                                    if ($_POST['usuario_senha'] !== $_POST['usuario_senha2']) {
                                        $layoutClass->alert('danger', 'As senhas não conferem.', 3);
                                    } elseif (strlen($_POST['usuario_senha']) < 6) {
                                        $layoutClass->alert('danger', 'A senha deve ter pelo menos 6 caracteres.', 3);
                                    } else {
                                        $usuario = [
                                            'usuario_nome' => $_POST['usuario_nome'],
                                            'usuario_email' => $_POST['usuario_email'],
                                            'usuario_telefone' => $_POST['usuario_telefone'],
                                            'usuario_aniversario' => $_POST['usuario_aniversario'],
                                            'usuario_ativo' => $_POST['usuario_ativo'],
                                            'usuario_nivel' => $_POST['usuario_nivel'],
                                            'usuario_senha' => $_POST['usuario_senha'],
                                            'foto' => $_FILES['foto']
                                        ];
                                        $resultado = $usuarioController->novoUsuario($usuario);

                                        if ($resultado['status'] === 'success') {
                                            $layoutClass->alert('success', $resultado['message'], 3);
                                        } else if ($resultado['status'] === 'file_not_permitted' || $resultado['status'] === 'duplicated' || $resultado['status'] === 'file_too_large') {
                                            $layoutClass->alert('info', $resultado['message'], 3);
                                        } else if ($resultado['status'] === 'error' || $resultado['status'] === 'forbidden') {
                                            $layoutClass->alert('danger', $resultado['message'], 3);
                                        }
                                    }
                                }

                                ?>
                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
                                    <div class="col-md-6 col-12">
                                        <input type="text" class="form-control form-control-sm" name="usuario_nome" placeholder="Nome" required>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input type="email" class="form-control form-control-sm" name="usuario_email" placeholder="Email" required>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="text" class="form-control form-control-sm" name="usuario_telefone" placeholder="Celular (com DDD)" maxlength="11" required>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="date" class="form-control form-control-sm" name="usuario_aniversario" required>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" name="usuario_ativo" required>
                                            <option value="1" selected>Ativado</option>
                                            <option value="0">Desativado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" name="usuario_nivel" required>
                                            <option value="1">Administrador</option>
                                            <option value="2" selected>Assessor</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="password" class="form-control form-control-sm" name="usuario_senha" placeholder="Senha" required>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="password" class="form-control form-control-sm" name="usuario_senha2" placeholder="Confirme a senha" required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="file-upload">
                                            <input type="file" id="file-input" name="foto" style="display: none;" />
                                            <button id="file-button" type="button" class="btn btn-primary btn-sm"><i class="fa-regular fa-image"></i> Escolher Foto</button>
                                            <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $usuarios = $usuarioController->ListarUsuarios();
                $tabela = [];

                if ($usuarios['status'] == 'success' && $usuarios['status'] != 'empty') {
                    foreach ($usuarios['dados'] as $usuario) {
                        $tabela[] = [
                            'Nome' => '<a href="editar-usuario.php?id=' . $usuario['usuario_id'] . '">' . $usuario['usuario_nome'] . '</a>',
                            'Email' => $usuario['usuario_email'],
                            'Telefone' => $usuario['usuario_telefone'],
                            'Aniversário' => date('d/m', strtotime($usuario['usuario_aniversario'])),
                            'Ativo' => $usuario['usuario_ativo'] ? 'Ativo' : 'Desativado',
                            'Nível' => $usuario['usuario_nivel'] == 1 ? 'Administrador' : 'Assessor'
                        ];
                    }
                    echo $layoutClass->criarTabela($tabela);
                } else if ($usuarios['status'] == 'error') {
                    echo $layoutClass->criarTabela([['Mensagem' => 'Erro interno do servidor.']]);
                } else {
                    echo $layoutClass->criarTabela([]);
                }

                ?>


            </div>
        </div>
    </div>
    <script>
        $('#file-button').on('click', function() {
            $('#file-input').click();
        });

        $('#file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $('#file-button').html(fileName ? '<i class="fa-regular fa-circle-check"></i> Foto selecionada' : 'Nenhuma foto selecionada');
        });
    </script>

</body>

</html>