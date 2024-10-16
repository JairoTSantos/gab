<?php

require_once dirname(__DIR__) . '/app/controllers/ComissoesController.php';
$comissoesController = new ComissoesController();


$a =  $comissoesController->ListarCargos(2012);

print_r($a);