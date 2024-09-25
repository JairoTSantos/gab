<?php 
require_once dirname(__DIR__) . '/app/controllers/PessoaController.php';
$pessoaController = new PessoaController();


print_r($pessoaController->InserirDeputados());