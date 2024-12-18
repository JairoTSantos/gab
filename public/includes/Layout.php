<?php



class Layout {

    public function MontarHead($titulo) {
        echo '<title>Gabinete Digital :: ' . $titulo . '</title>
              <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
              <link href="vendor/startbootstrap-simple-sidebar-gh-pages/css/styles.css" rel="stylesheet" />
               <link href="css/custom.css" rel="stylesheet" />
                <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
              <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
              <script src="vendor/startbootstrap-simple-sidebar-gh-pages/js/scripts.js"></script>
             
              <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>


        <script src="https://kit.fontawesome.com/632988d903.js" crossorigin="anonymous"></script>';
    }


    public function MontarTopMenu() {
        echo '<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                    <div class="container-fluid">
                        <button class="btn btn-primary" id="sidebarToggle" style="font-size:1.0em"><i class="fa-solid fa-bars"></i> Menu</button>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                             <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle"  id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Configurações</a>
                                     <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="profissoes.php" style="font-size:0.8em"><i class="fa-solid fa-user-tie"></i> Profissões</a>
                                        <a class="dropdown-item" href="pessoas-tipos.php" style="font-size:0.8em"><i class="fa-solid fa-users-gear"></i> Tipos de pessoas</a>
                                        <hr class="dropdown-divider">
                                        <a class="dropdown-item" href="orgaos-tipos.php" style="font-size:0.8em"><i class="fa-solid fa-building-flag"></i> Tipos de órgãos ou instituições</a>
                                        <hr class="dropdown-divider">
                                         <a class="dropdown-item" href="postagens_status.php" style="font-size:0.8em"><i class="fa-regular fa-thumbs-up"></i> Status das postagens</a>
                                         <hr class="dropdown-divider">
                                         <a class="dropdown-item" href="clipping-tipo.php" style="font-size:0.8em"><i class="fa-solid fa-clipboard"></i> Tipo de clipping</a>
                                         <!--<hr class="dropdown-divider">
                                         <a class="dropdown-item" href="atualizar_comissoes.php" style="font-size:0.8em"><i class="fa-solid fa-users-between-lines"></i>  Atualizar Comissoes</a>-->
                                         <hr class="dropdown-divider">
                                         <a class="dropdown-item" href="usuarios.php" style="font-size:0.8em"><i class="fa-solid fa-users"></i> Usuários</a>
                                    </div>
                                   
                                </li>
                               
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle"  id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $_SESSION['usuario_nome'] . '</a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="sair.php" style="font-size:0.8em"><i class="fa-solid fa-door-open"></i> Sair</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>';
    }


    public function MontarSideMenu() {
        echo ' <div class="border-end bg-white" id="sidebar-wrapper">
                <div class="sidebar-heading border-bottom bg-light"><img src="img/logo.png" style="width:25px" />&nbsp;&nbsp;Gabinete Digital</div>
                <div class="list-group list-group-flush">

                    <!--<p style="margin-left: 10px; margin-top:20px;font-weight: bolder;" class="text-muted"><i class="fas fa-bars"></i> Legislativo</p> 
                    <a class="list-group-item list-group-item-action list-group-item-light px-4" href="comissoes.php"><i class="fa-solid fa-users-between-lines"></i> Comissões</a>-->
                    <p style="margin-left: 10px; margin-top:20px;font-weight: bolder;" class="text-muted"><i class="fas fa-bars"></i> Gestão de pessoas</p> 
                    <a class="list-group-item list-group-item-action list-group-item-light px-4" href="orgaos.php"><i class="fa-solid fa-building-columns"></i> Órgãos e instituições</a>
                    <a class="list-group-item list-group-item-action list-group-item-light px-4" href="pessoas.php"><i class="fa-solid fa-person"></i> Pessoas</a>
                    <p style="margin-left: 10px; margin-top:20px;font-weight: bolder;" class="text-muted"><i class="fas fa-bars"></i> Gestão do gabinete</p> 
                    <a class="list-group-item list-group-item-action list-group-item-light px-4" href="oficios.php"><i class="fa-regular fa-file-lines"></i>  Ofícios</a>
                    
                    <p style="margin-left: 10px; margin-top:20px;font-weight: bolder;" class="text-muted"><i class="fas fa-bars"></i> Comunicação</p> 
                    <a class="list-group-item list-group-item-action list-group-item-light px-4" href="postagens.php"><i class="fa-regular fa-newspaper"></i>  Postagens</a>
                    <a class="list-group-item list-group-item-action list-group-item-light px-4" href="clipping.php"><i class="fa-solid fa-clipboard"></i>  Clipping</a>
                </div>
            </div>';
    }

    function navBar($voltar = false, $url = 'home.php', $print = false) {
        echo '<div class="card mb-2 ">
                <div class="card-body p-1">
                    <a class="btn btn-primary btn-sm custom-nav " href="home.php" role="button"><i class="fa-solid fa-house"></i> Início</a>&nbsp;';

        if ($voltar) {
            echo '<a class="btn btn-success btn-sm custom-nav" href="' . $url . '"role="button"><i class="fa-solid fa-arrow-left"></i> Voltar</a>&nbsp;';
        }
        if ($print) {
            echo '<a class="btn btn-secondary btn-sm custom-nav" href="#" onclick="alert(\'Implementar o metodo de impressao\');" role="button"><i class="fa-solid fa-print"></i> Imprimir</a>';
        }

        echo '  </div>
            </div>';
    }


    function cardDescription($titulo, $message) {
        echo '<div class="card mb-2 card_description">
                <div class="card-header bg-primary text-white px-2 py-1  card-background">' . $titulo . '</div>
                <div class="card-body p-2">
                 ' . $message . '
                </div>
            </div>';
    }

    public function criarTabela(array $dados = []) {
        // Inicia o HTML básico da tabela
        $html = <<<HTML
        <div class="row mb-2">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0 custom_table">
        HTML;
    
        // Verifica se o array está vazio
        if (empty($dados)) {
            // Retorna a mensagem de "Nenhum registro encontrado"
            $html .= <<<HTML
                                <thead>
                                    <tr>
                                        <th>Mensagem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Nenhum registro encontrado.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        HTML;
        } else {
            // Pega as chaves do primeiro elemento do array para os cabeçalhos da tabela
            $chaves = array_keys(reset($dados));
    
            // Monta o cabeçalho da tabela
            $html .= '<thead><tr>';
            foreach ($chaves as $chave) {
                $html .= "<th style='white-space: nowrap;'>{$chave}</th>";
            }
            $html .= '</tr></thead><tbody>';
    
            // Monta as linhas de dados
            foreach ($dados as $linha) {
                $html .= '<tr>';
                foreach ($chaves as $chave) {
                    // Permite apenas algumas tags HTML, como links <a>
                    $valor = strip_tags($linha[$chave], '<a><b><i><strong><em>'); 
                    $estilo = (strlen(strip_tags($valor)) > 15) ? 'style="word-break: break-word;"' : 'style="white-space: nowrap;"';
                    $html .= "<td {$estilo}>{$valor}</td>";
                }
                $html .= '</tr>';
            }
    
            // Fecha a tabela
            $html .= '</tbody></table></div></div></div></div></div>';
        }
    
        return $html;
    }
    
    
    



    function alert($type, $message, $time = 3, $rounded = false) {
        // Exibe o alerta com a classe Bootstrap

        if ($rounded) {
            echo '<div id="alert-box" style="border: none; border-radius: 20px;" class="alert alert-' . $type . ' custom_alert alert-dismissible fade show px-2 py-1 mb-2" role="alert"><b>' . $message . '</b></div>';
        } else {
            echo '<div id="alert-box" class="alert alert-' . $type . ' custom_alert alert-dismissible fade show px-2 py-1 mb-2" role="alert"><b>' . $message . '</b></div>';
        }



        // Adiciona o script para remover o alerta após o tempo especificado
        if ($time > 0) {
            echo '<script>
                setTimeout(function() {
                    var alertBox = document.getElementById("alert-box");
                    if (alertBox) {
                        // Adiciona a classe de fade out
                        alertBox.classList.remove("show");
                        alertBox.classList.add("fade");
                        // Remove o alerta após o tempo da animação de fade out
                        setTimeout(function() {
                            alertBox.remove();
                        }, 300); // Tempo para garantir que a animação de fade-out seja concluída
                    }
                }, ' . ($time * 1000) . ');
            </script>';
        }
    }
}
