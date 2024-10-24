<?php

$ano = date('Y');
$inicioLegislatura55 = 2015;  
$legislatura = 55; 
$duracaoLegislatura = 4; 

$legislaturaAtual = $legislatura + floor(($ano - $inicioLegislatura55) / $duracaoLegislatura);

return [
    'db' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'database' => 'gabinete_digital'
    ],
    'master_user' => [
        'name' => 'Administrador',
        'email' => 'admin@admin.com',
        'pass' => 'intell01'
    ],
    'app' => [
        'maximum_file_size' => 100,
        'permitted_files' => ['png', 'jpg', 'jpeg', 'docx', 'pdf', 'doc', 'psd', 'ai', 'zip', 'mp4', 'mov']
    ],
    'deputado' => [
        'id_deputado' => 204379,
        'nome_deputado' => 'ACÁCIO FAVACHO',
        'estado_deputado' => 'AP',
        'partido_deputado' => 'MDB',
        'legislatura_atual' => $legislaturaAtual,
        'ano_primeira_legislatura' => 2019
    ]
];
