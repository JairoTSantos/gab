<?php

$ano = date('Y');
$inicioLegislatura55 = 2015;  
$legislatura = 55;  // Legislatura inicial
$duracaoLegislatura = 4;  // Cada legislatura dura 4 anos

// Calcula a legislatura atual com base no ano corrente
$legislaturaAtual = $legislatura + floor(($ano - $inicioLegislatura55) / $duracaoLegislatura);

return [
    'db' => [
        'host' => 'localhost',
        'username' => 'jairo',
        'password' => 'intell01',
        'database' => 'gab'
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
        'nome_deputado' => 'Acácio Favacho',
        'estado_deputado' => 'AP',
        'legislatura_atual' => $legislaturaAtual
    ]
];
