<?php

require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/UsuarioController.php';
$usuarioController = new UsuarioController();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$buscaUsuario = $usuarioController->BuscarUsuario('usuario_id', $id);

if ($buscaUsuario['status'] == 'empty' || $buscaUsuario['status'] == 'error') {
    header('Location: usuarios.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Editar Usuário ::' . $buscaUsuario['dados']['usuario_nome']) ?>
</head>


<body class="bg-secondary">

    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar(true, 'usuarios.php') ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm mb-2 card-background">
                            <div class="card-body p-2">
                                <div class="row">
                                    <div class="col-12 col-md-1">
                                        <?php
                                        if (isset($buscaUsuario['dados']['usuario_foto'])) {
                                            echo '<img src="..' . $buscaUsuario['dados']['usuario_foto'] . '" class="img-thumbnail img-crop" alt="...">';
                                        } else {
                                            echo '<img src="img/not_found.jpg" class="img-thumbnail img-crop" alt="...">';
                                        }
                                        ?>
                                    </div>
                                    <div class="col-12 col-md-11 mt-2 ">
                                        <h5 class="card-title"><?php echo $buscaUsuario['dados']['usuario_nome']; ?></h5>
                                        <p class="card-text mb-2"><i class="fa-solid fa-envelope"></i> <?php echo $buscaUsuario['dados']['usuario_email']; ?></p>
                                        <p class="card-text mb-2"><i class="fa-solid fa-mobile-screen"></i> <?php echo $buscaUsuario['dados']['usuario_telefone']; ?></p>
                                        <p class="card-text mb-2"><i class="fa-solid fa-cake-candles"></i> <?php echo date('d/M', strtotime($buscaUsuario['dados']['usuario_aniversario'])); ?></p>
                                    </div>
                                </div>
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
                                        'usuario_nome' => $_POST['usuario_nome'],
                                        'usuario_email' => $_POST['usuario_email'],
                                        'usuario_telefone' => $_POST['usuario_telefone'],
                                        'usuario_aniversario' => $_POST['usuario_aniversario'],
                                        'usuario_ativo' => $_POST['usuario_ativo'],
                                        'usuario_nivel' => $_POST['usuario_nivel'],
                                        'foto' => isset($_FILES['foto']) ? $_FILES['foto'] : null
                                    ];
                                    $resultado = $usuarioController->AtualizarUsuario($id, $usuario);


                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                        echo '<script>
                                                    setTimeout(function(){
                                                        window.location.href = "editar-usuario.php?id=' . $id . '";
                                                    }, 1000);
                                                </script>';
                                    } else if ($resultado['status'] === 'file_not_permitted' || $resultado['status'] === 'duplicated' || $resultado['status'] === 'file_too_large' || $resultado['status'] === 'bad_request') {
                                        $layoutClass->alert('info', $resultado['message'], 3);
                                    } else if ($resultado['status'] === 'error' || $resultado['status'] === 'forbidden') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                                    $resultado = $usuarioController->ApagarUsuario($id);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                        echo '<script>
                                                    setTimeout(function(){
                                                        window.location.href = "usuarios.php";
                                                    }, 500);
                                                </script>';
                                    } elseif ($resultado['status'] === 'error' || $resultado['status'] === 'invalid_id' || $resultado['status'] === 'delete_conflict') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }


                                ?>
                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
                                    <div class="col-md-6 col-12">
                                        <input type="text" class="form-control form-control-sm" name="usuario_nome" placeholder="Nome" value="<?php echo $buscaUsuario['dados']['usuario_nome'] ?>" required>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input type="email" class="form-control form-control-sm" name="usuario_email" placeholder="Email" value="<?php echo $buscaUsuario['dados']['usuario_email'] ?>" required>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="text" class="form-control form-control-sm" name="usuario_telefone" placeholder="Celular (com DDD)" value="<?php echo $buscaUsuario['dados']['usuario_telefone'] ?>" maxlength="11" required>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="date" class="form-control form-control-sm" name="usuario_aniversario" value="<?php echo $buscaUsuario['dados']['usuario_aniversario'] ?>" required>
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

                                    <div class="col-md-3 col-12">
                                        <div class="file-upload">
                                            <input type="file" id="file-input" name="foto" style="display: none;" />
                                            <button id="file-button" type="button" class="btn btn-primary btn-sm"><i class="fa-regular fa-image"></i> Escolher Foto</button>
                                            <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
                                            <button type="submit" class="btn btn-danger btn-sm" name="btn_apagar"><i class="fa-solid fa-trash"></i> Apagar</button>
                                        </div>
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
        $('#file-button').on('click', function() {
            $('#file-input').click();
        });

        $('#file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $('#file-button').html(fileName ? '<i class="fa-regular fa-circle-check"></i> Foto selecionada' : 'Nenhuma foto selecionada');
        });

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
    </script>

</body>

</html>