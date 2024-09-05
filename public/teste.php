<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Cadastro de Usuário</title>
</head>

<body>
    <h1>Cadastro de Usuário</h1>


    <?php

    require_once dirname(__DIR__) . '/app/controllers/usuarioController.php';

    $usuarioController = new UsuarioController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {


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
        $resultado = $usuarioController->atualizarUsuario(1011, $usuario);


        print_r($resultado);
    }


    ?>



    <form action="" method="post" enctype="multipart/form-data">
        <label for="usuario_nome">Nome:</label>
        <input type="text" id="usuario_nome" name="usuario_nome" required><br><br>

        <label for="usuario_email">Email:</label>
        <input type="email" id="usuario_email" name="usuario_email" required><br><br>

        <label for="usuario_telefone">Telefone:</label>
        <input type="tel" id="usuario_telefone" name="usuario_telefone" required><br><br>

        <label for="usuario_senha">Senha:</label>
        <input type="password" id="usuario_senha" name="usuario_senha" required><br><br>

        <label for="usuario_nivel">Nível:</label>
        <select id="usuario_nivel" name="usuario_nivel" required>
            <option value="1">Admin</option>
            <option value="2">Usuário</option>
        </select><br><br>

        <label for="usuario_ativo">Ativo:</label>
        <select id="usuario_ativo" name="usuario_ativo" required>
            <option value="1">Sim</option>
            <option value="0">Não</option>
        </select><br><br>

        <label for="usuario_aniversario">Aniversário:</label>
        <input type="date" id="usuario_aniversario" name="usuario_aniversario" required><br><br>

        <label for="foto">Foto:</label>
        <input type="file" id="foto" name="foto"><br><br>

        <input type="submit" value="Cadastrar">
    </form>
</body>

</html>