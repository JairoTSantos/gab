<?php

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
        'nome_deputado' => 'Acácio Favacho',
        'estado_deputado' => 'AP',
        'legislatura_atual' => 57
    ]
];
