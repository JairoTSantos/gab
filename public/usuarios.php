<?php

require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/verificaLogado.php';

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php montarHeader('Usuários'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php include 'includes/side_menu.php' ?>
        <div id="page-content-wrapper">
            <?php include 'includes/top_menu.php' ?>
            <div class="container-fluid p-2">
                <?php navBar(); ?>
                <?php cardDescription('<i class="fa-solid fa-user-tie"></i> Usuários', 'Por favor, preencha os dados do usuário para inseri-lo no sistema. <b>Todos os campos são abrigatórios</b>'); ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">

                                <?php
                                require_once dirname(__DIR__) . '/app/controllers/usuarioController.php';

                                $usuarioController = new UsuarioController();

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {

                                    if ($_POST['usuario_senha'] !== $_POST['usuario_senha2']) {
                                        alert('danger', 'As senhas não conferem.', 3);
                                    } elseif (strlen($_POST['usuario_senha']) < 6) {
                                        alert('danger', 'A senha deve ter pelo menos 6 caracteres.', 3);
                                    } else {
                                        $usuario = [
                                            'usuario_nome' => $_POST['usuario_nome'],
                                            'usuario_email' => $_POST['usuario_email'],
                                            'usuario_telefone' => $_POST['usuario_telefone'],
                                            'usuario_aniversario' => $_POST['usuario_aniversario'],
                                            'usuario_ativo' => $_POST['usuario_ativo'],
                                            'usuario_nivel' => $_POST['usuario_nivel'],
                                            'usuario_senha' => $_POST['usuario_senha']
                                        ];
                                        $resultado = $usuarioController->novoUsuario($usuario);

                                        if ($resultado['status'] === 'success') {
                                            alert('success', $resultado['message'], 3);
                                        } elseif ($resultado['status'] === 'duplicated' || $resultado['status'] === 'invalid_email' || $resultado['status'] === 'bad_request') {
                                            alert('info', $resultado['message'], 3);
                                        } elseif ($resultado['status'] === 'error') {
                                            alert('danger', $resultado['message'], 3);
                                        }
                                    }
                                }

                                ?>
                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="application/x-www-form-urlencoded">
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
                                    <div class="col-md-4 col-6">
                                        <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body p-2">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered mb-0 custom_table">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Email</th>
                                                <th>Telefone</th>
                                                <th>Aniversário</th>
                                                <th>Nível</th>
                                                <th>Ativo</th>
                                                <th>Criado - Atualizado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $busca = $usuarioController->listarUsuarios();

                                            if ($busca['status'] === 'success') {
                                                foreach ($busca['dados'] as $usuario) {
                                                    $criado = date('d/m H:i', strtotime($usuario['usuario_criado_em']));
                                                    $atualizado = date('d/m H:i', strtotime($usuario['usuario_atualizado_em']));
                                                    echo '
                                                            <tr>
                                                                <td><a href="./usuarios/' . $usuario['usuario_id'] . '" id="link">' . $usuario['usuario_nome'] . '</a></td>
                                                                <td>' . $usuario['usuario_email'] . '</td>
                                                                <td>' . $usuario['usuario_telefone'] . '</td>
                                                                <td>' . date('d/m', strtotime($usuario['usuario_aniversario'])) . '</td>
                                                                <td>' . ($usuario['usuario_nivel'] == 1 ? '<b>Administrador</b>' : 'Assessor') . '</td>
                                                                <td>' . ($usuario['usuario_ativo'] == 1 ? 'Ativo' : 'Inativo') . '</td>
                                                                <td>' . (($criado === $atualizado) ? $criado : $criado . ' - ' . $atualizado) . '</td>
                                                            </tr>
                                                        ';
                                                }
                                            } else if ($busca['status'] === 'empty') {
                                                echo '<tr><td colspan="7">Nenhum usuário registrado.</td></tr>';
                                            } else if ($busca['status'] === 'error') {
                                                echo '<tr><td colspan="7">Erro interno do servidor.</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>