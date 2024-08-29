<?php
$config = require '../app/config/config.php';

?>
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container-fluid">
        <button class="btn btn-primary" id="sidebarToggle" style="font-size: 1.1em;">Menu</button>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mt-2 mt-lg-0" style="font-size: 1.1em;">
                <!--<li class="nav-item active"><a class="nav-link" href="#!">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#!">Link</a></li>-->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Configurações</a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="font-size: 0.9em;">

                        <a class="dropdown-item" href="<?php echo $config['app']['url'] ?>/usuarios"><i class="fa-solid fa-users"></i> Usuários</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['usuario_nome'] ?></a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="font-size: 0.9em;">
                        <a class="dropdown-item" href="#!"><i class="fa-regular fa-envelope"></i> Mensagens</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo $config['app']['url'] ?>/sair"><i class="fa-solid fa-door-open"></i> Sair</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>