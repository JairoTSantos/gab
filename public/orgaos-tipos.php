<?php

require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/app/controllers/OrgaoController.php';

$orgaoController = new OrgaoController();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php montarHeader('Tipos de Órgãos'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php include 'includes/side_menu.php' ?>
        <div id="page-content-wrapper">
            <?php include 'includes/top_menu.php' ?>
            <div class="container-fluid p-2">
                <?php navBar(); ?>
                <?php cardDescription('<i class="fa-regular fa-building"></i> Tipos de órgãos e instituições', '<p class="card-text mb-0">Por favor, preencha os campos para criar novos tipos de órgão e instituições</p>'); ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">

                                <?php

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {
                                    
                                    $tipo = [
                                        'orgao_tipo_nome' => $_POST['nome'],
                                        'orgao_tipo_descricao' => $_POST['descricao'],
                                    ];

                                    $resultado = $orgaoController->novoTipo($tipo);

                                    if ($resultado['status'] === 'success') {
                                        alert('success', $resultado['message'], 3);
                                    } elseif ($resultado['status'] === 'duplicated' || $resultado['status'] === 'invalid_email' || $resultado['status'] === 'bad_request') {
                                        alert('info', $resultado['message'], 3);
                                    } elseif ($resultado['status'] === 'error') {
                                        alert('danger', $resultado['message'], 3);
                                    }
                                }

                                ?>

                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-12 col-12">
                                        <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome " required>
                                    </div>
                                    
                                    <div class="col-md-12 col-12">
                                        <textarea class="form-control form-control-sm" name="descricao" rows="5" placeholder="Descrição"></textarea>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body p-2">
                                <div class="table-responsive">

                                    <table class="table table-striped table-bordered mb-2 custom_table">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Descricao</th>
                                                <th>Criado por | em</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $buscaTipo = $orgaoController->listarTiposOrgaos();

                                            if ($buscaTipo['status'] === 'success') {

                                                foreach ($buscaTipo['dados'] as $tipo) {
                                                    echo '
                                                        <tr>
                                                            <td>' . $tipo['orgao_tipo_nome'] . '</td>
                                                            <td>' . $tipo['orgao_tipo_descricao'] . '</td>
                                                            <td>' . $tipo['usuario_nome'] . ' | '.date('d/m : H:i', strtotime($tipo['orgao_tipo_criado_em'])).'</td>
                                                        </tr>
                                                    ';
                                                }
                                            } else if ($buscaTipo['status'] === 'empty') {
                                                echo '<tr><td colspan="10">Nenhum tipo registrado.</td></tr>';
                                            } else if ($buscaTipo['status'] === 'error') {
                                                echo '<tr><td colspan="7">Erro interno do servidor.</td></tr>';
                                            }

                                            ?>
                                        </tbody>
                                    </table>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</body>

</html>