


    <?php

    require_once dirname(__DIR__) . '/app/controllers/usuarioController.php';

    $usuarioController = new UsuarioController();

    print_r($usuarioController->ListarUsuarios());