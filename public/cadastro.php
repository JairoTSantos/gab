<?php

require_once __DIR__ . '/includes/layout.php';

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php montarHeader('Cadastro'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="vh-100 d-flex align-items-center justify-content-center">
                            <div class="card mx-auto" style="width: 45em">
                                <div class="card-body p-2">
                                    <?php

                                    session_start();
                                    $_SESSION['usuario_nivel'] = 1;

                                    require_once dirname(__DIR__) . '/app/controllers/usuarioController.php';

                                    $usuarioController = new UsuarioController();

                                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_login'])) {

                                        $usuario_nome = isset($_POST['usuario_nome']) ? htmlspecialchars(trim($_POST['usuario_nome'])) : '';
                                        $usuario_email = isset($_POST['usuario_email']) ? filter_var(trim($_POST['usuario_email']), FILTER_VALIDATE_EMAIL) : '';
                                        $usuario_telefone = isset($_POST['usuario_telefone']) ? preg_replace('/[^0-9]/', '', trim($_POST['usuario_telefone'])) : '';
                                        $usuario_aniversario = isset($_POST['usuario_aniversario']) ? trim($_POST['usuario_aniversario']) : '';
                                        $usuario_ativo = 0;
                                        $usuario_nivel = 2;
                                        $usuario_senha = isset($_POST['usuario_senha']) ? htmlspecialchars(trim($_POST['usuario_senha'])) : '';
                                        $usuario_senha2 = isset($_POST['usuario_senha2']) ? htmlspecialchars(trim($_POST['usuario_senha2'])) : '';

                                        if ($usuario_senha !== $usuario_senha2) {
                                            alert('danger', 'As senhas não conferem.', 3);
                                        } elseif (strlen($usuario_senha) < 6) {
                                            alert('danger', 'A senha deve ter pelo menos 6 caracteres.', 3);
                                        } else {
                                            $usuario = [
                                                'usuario_nome' => $usuario_nome,
                                                'usuario_email' => $usuario_email,
                                                'usuario_telefone' => $usuario_telefone,
                                                'usuario_aniversario' => $usuario_aniversario,
                                                'usuario_ativo' => $usuario_ativo,
                                                'usuario_nivel' => $usuario_nivel,
                                                'usuario_senha' => $usuario_senha
                                            ];
                                            $resultado = $usuarioController->novoUsuario($usuario);

                                            if ($resultado['status'] === 'success') {
                                                alert('success', $resultado['message'] . ' Aguarde sua conta ser ativada. <a href="./login">Voltar para login   </b>', 0);
                                            } elseif ($resultado['status'] === 'duplicated' || $resultado['status'] === 'invalid_email' || $resultado['status'] === 'bad_request') {
                                                alert('info', $resultado['message'], 3);
                                            } elseif ($resultado['status'] === 'error') {
                                                alert('danger', $resultado['message'], 3);
                                            }
                                        }
                                        session_destroy();
                                    }

                                    ?>

                                    <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="application/x-www-form-urlencoded">
                                        <div class="col-md-6 col-12">
                                            <input type="text" class="form-control form-control-sm" name="usuario_nome" placeholder="Nome" required>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <input type="email" class="form-control form-control-sm" name="usuario_email" placeholder="Email" required>
                                        </div>
                                        <div class="col-md-6 col-6">
                                            <input type="text" class="form-control form-control-sm" name="usuario_telefone" placeholder="Celular (com DDD)" maxlength="11" required>
                                        </div>
                                        <div class="col-md-6 col-6">
                                            <input type="date" class="form-control form-control-sm" name="usuario_aniversario" required>
                                        </div>

                                        <div class="col-md-6 col-6">
                                            <input type="password" class="form-control form-control-sm" name="usuario_senha" placeholder="Senha" required>
                                        </div>
                                        <div class="col-md-6 col-6">
                                            <input type="password" class="form-control form-control-sm" name="usuario_senha2" placeholder="Confirme a senha" required>
                                        </div>
                                        <div class="col-md-4 col-6">
                                            <button type="submit" class="btn btn-success btn-sm" name="btn_login"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
                                        </div>


                                    </form>
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