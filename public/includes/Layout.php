<?php



class Layout {

    public function MontarHead($titulo) {
        echo '<title>Gabinete Digital :: ' . $titulo . '</title>
              <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
              <link href="vendor/startbootstrap-simple-sidebar-gh-pages/css/styles.css" rel="stylesheet" />
               <link href="css/custom.css" rel="stylesheet" />
              <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
              <script src="vendor/startbootstrap-simple-sidebar-gh-pages/js/scripts.js"></script>
               <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/632988d903.js" crossorigin="anonymous"></script>';
    }


    public function MontarTopMenu() {
        echo '<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                    <div class="container-fluid">
                        <button class="btn btn-primary" id="sidebarToggle">Toggle Menu</button>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                                <li class="nav-item active"><a class="nav-link" href="#!">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="#!">Link</a></li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="#!">Action</a>
                                        <a class="dropdown-item" href="#!">Another action</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#!">Something else here</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>';
    }


    public function MontarSideMenu() {
        echo ' <div class="border-end bg-white" id="sidebar-wrapper">
                <div class="sidebar-heading border-bottom bg-light">Start Bootstrap</div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Dashboard</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Shortcuts</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Overview</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Events</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Profile</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Status</a>
                </div>
            </div>';
    }

    function navBar($voltar = false, $url = '', $print = false) {
        echo '<div class="card mb-2 ">
                <div class="card-body p-1">
                    <a class="btn btn-primary btn-sm custom-nav " href="' . $url . '" role="button"><i class="fa-solid fa-house"></i> Início</a>&nbsp;';

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
                <div class="card-header bg-primary text-white px-2 py-1">' . $titulo . '</div>
                <div class="card-body p-2">
                 ' . $message . '
                </div>
            </div>';
    }

    public function criarTabela(array $dados = []) {
        if (empty($dados)) {
            return <<<HTML
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body p-2">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered mb-0 custom_table">
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
        }

        $chaves = array_keys(reset($dados));

        $html = <<<HTML
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0 custom_table">
                                    <thead>
                                        <tr>
        HTML;

        foreach ($chaves as $chave) {
            $html .= "<th>{$chave}</th>";
        }

        $html .= <<<HTML
                                        </tr>
                                    </thead>
                                    <tbody>
        HTML;

        foreach ($dados as $linha) {
            $html .= '<tr>';
            foreach ($chaves as $chave) {
                $html .= "<td>{$linha[$chave]}</td>";
            }
            $html .= '</tr>';
        }

        $html .= <<<HTML
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        HTML;

        return $html;
    }


    function alert($type, $message, $time = 3) {
        // Exibe o alerta com a classe Bootstrap
        echo '<div id="alert-box" class="alert alert-' . $type . ' custom_alert alert-dismissible fade show px-2 py-1 mb-2" role="alert"><b>' . $message . '</b></div>';
    
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