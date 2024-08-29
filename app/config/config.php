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
        'session_time' => 86400,
        'app_folder' => $protocol . $host . $uri,
        'url' => '/' . basename(dirname(dirname(__DIR__)))
       
    ],
    'deputado' => [
        'id_deputado' => 204379,
        'nome_deputado' => 'Acácio Favacho',
        'inicio_legislatura' => '2019-01-01',
        'fim_legislatura' => '2028-12-31',
        'legislatura_atual' => 57
    ]
];
