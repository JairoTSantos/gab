<?php


function montarHeader($titulo) {
    $config = require '../app/config/config.php';
    echo '
        <title>Gabinete Digital ::  ' . $titulo . '</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <link href="' . $config['app']['app_folder'] . '/css/styles.css" rel="stylesheet" />
        <link href="' . $config['app']['app_folder'] . '/css/custom.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/632988d903.js" crossorigin="anonymous"></script>
        <script src="' . $config['app']['app_folder'] . '/js/scripts.js"></script>
    ';
}


function navBar($voltar = false, $url = '', $print = false) {
    $config = require '../app/config/config.php';
    echo '<div class="card mb-2 ">
            <div class="card-body p-1">
                <a class="btn btn-primary btn-sm custom-nav " href="'.$config['app']['url'].'/home" role="button"><i class="fa-solid fa-house"></i> Início</a>&nbsp;';

    if ($voltar) {
        echo '<a class="btn btn-success btn-sm custom-nav" href="' . $url . '" role="button"><i class="fa-solid fa-arrow-left"></i> Voltar</a>&nbsp;';
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
