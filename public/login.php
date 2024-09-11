<?php

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__,) . '/app/core/Login.php';
$login = new Login();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Login'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="vh-100 d-flex align-items-center justify-content-center">
                            <div class="card mx-auto" style="width: 35em">
                                <div class="card-body p-2">
                                    <?php

                                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_login'])) {
                                        $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : '';
                                        $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';

                                        $dados = [
                                            'email' => $email,
                                            'senha' => $senha
                                        ];

                                        $resultado = $login->Logar($dados);

                                        if ($resultado['status'] === 'not_found' || $resultado['status'] === 'deactivated') {
                                            $layoutClass->alert('info', $resultado['message'], 3);
                                        } else if ($resultado['status'] === 'error' || $resultado['status'] === 'wrong_password') {
                                            $layoutClass->alert('danger', $resultado['message'], 3);
                                        } else {
                                            header('Location: home.php');
                                        }
                                    }

                                    ?>

                                    <form id="form_login" method="post" enctype="application/x-www-form-urlencoded" class="form-group form_custom">
                                        <div class="form-group">
                                            <input type="email" class="form-control mb-2" name="email" id="email" placeholder="E-mail" value="jairojeffersont@gmail.com" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control mb-2" name="senha" id="senha" placeholder="Insira sua senha" value="intell01" required>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <button type="submit" name="btn_login" class="btn btn-primary">Entrar</button>
                                            <a href="cadastro.php"><small>Cadastre-se</small></a>
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