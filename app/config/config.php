<?php

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$uri = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');


return [
    'db' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'database' => 'api'
    ],
    'master_user' => [
        'name' => 'Administrador',
        'email' => 'admin@admin.com',
        'pass' => 'intell01'
    ],
    'app' => [
        'maximum_file_size' => 5, //MB
        'permitted_files' => ['png', 'jpg', 'jpeg', 'docx', 'pdf', 'doc']
    ],
    'deputado' => [
        'id_deputado' => 204379,
        'nome_deputado' => 'Acácio Favacho',
        'legislatura_atual' => 57,
        'estado_deputado' => 'AP'
    ]
];
