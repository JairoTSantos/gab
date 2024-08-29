<?php

require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/app/controllers/ComissoesController.php';

$a = new ComissoesController();


print_r(json_encode($a->BuscarReunioes(false, '2024-08-28'), JSON_PRETTY_PRINT));