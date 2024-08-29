<?php

require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/verificaLogado.php';
require_once dirname(__DIR__) . '/app/controllers/usuarioController.php';

$usuarioController = new UsuarioController();

$id = $_GET['id'];

$buscaUsuario = $usuarioController->buscarUsuario('usuario_id', $id);

if ($buscaUsuario['status'] === 'empty') {
    header("Location: ../usuarios");
}

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
                <?php navBar(true, '../usuarios'); ?>
                <?php cardDescription('<i class="fa-solid fa-user-pen"></i> Editar usuario', 'Por favor, preencha os dados do usuário para atualiza-lo no sistema. <b>Todos os campos são abrigatórios</b>'); ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">

                                <?php

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {
                                    $usuario = [
                                        'usuario_nome' => $_POST['usuario_nome'],
                                        'usuario_email' => $_POST['usuario_email'],
                                        'usuario_telefone' => $_POST['usuario_telefone'],
                                        'usuario_aniversario' => $_POST['usuario_aniversario'],
                                        'usuario_ativo' => $_POST['usuario_ativo'],
                                        'usuario_nivel' => $_POST['usuario_nivel']
                                    ];
                                    $resultado = $usuarioController->atualizarUsuario($id, $usuario);

                                    if ($resultado['status'] === 'success') {
                                        alert('success', $resultado['message'], 3);
                                        $buscaUsuario = $usuarioController->buscarUsuario('usuario_id', $id);
                                    } elseif ($resultado['status'] === 'duplicated' || $resultado['status'] === 'invalid_email' || $resultado['status'] === 'bad_request') {
                                        alert('info', $resultado['message'], 3);
                                    } elseif ($resultado['status'] === 'error') {
                                        alert('danger', $resultado['message'], 3);
                                    }
                                }

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                                    $resultado = $usuarioController->apagarUsuario($id);

                                    if ($resultado['status'] === 'success') {
                                        alert('success', $resultado['message'], 3);
                                        echo '<script>
                                                setTimeout(function(){
                                                    window.location.href = "../usuarios";
                                                }, 1000);
                                            </script>';
                                    } elseif ($resultado['status'] === 'error' || $resultado['status'] === 'invalid_id' || $resultado['status'] === 'delete_conflict') {
                                        alert('danger', $resultado['message'], 3);
                                    }
                                }

                                ?>
                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-6 col-12">
                                        <input type="text" class="form-control form-control-sm" name="usuario_nome" placeholder="Nome " required value="<?php echo $buscaUsuario['dados']['usuario_nome'] ?>">
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input type="email" class="form-control form-control-sm" name="usuario_email" placeholder="Email" required value="<?php echo $buscaUsuario['dados']['usuario_email'] ?>">
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="text" class="form-control form-control-sm" name="usuario_telefone" placeholder="Celular (com DDD)" maxlength="11" required value="<?php echo $buscaUsuario['dados']['usuario_telefone'] ?>">
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="date" class="form-control form-control-sm" name="usuario_aniversario" required value="<?php echo $buscaUsuario['dados']['usuario_aniversario'] ?>">
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" name="usuario_ativo" required>
                                            <option value="1" <?php echo $buscaUsuario['dados']['usuario_ativo'] == 1 ? 'selected' : ''; ?>>Ativado</option>
                                            <option value="0" <?php echo $buscaUsuario['dados']['usuario_ativo'] == 0 ? 'selected' : ''; ?>>Desativado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" name="usuario_nivel" required>
                                            <option value="1" <?php echo $buscaUsuario['dados']['usuario_nivel'] == 1 ? 'selected' : ''; ?>>Administrador</option>
                                            <option value="2" <?php echo $buscaUsuario['dados']['usuario_nivel'] == 2 ? 'selected' : ''; ?>>Assessor</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-6">
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
        $(document).ready(function() {
            $('button[name="btn_apagar"]').on('click', function(event) {
                const confirmacao = confirm("Tem certeza que deseja apagar este usuário?");
                if (!confirmacao) {
                    event.preventDefault();
                }
            });

            $('button[name="btn_salvar"]').on('click', function(event) {
                const confirmacao = confirm("Tem certeza que deseja atualizar este usuário?");
                if (!confirmacao) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>

</html>