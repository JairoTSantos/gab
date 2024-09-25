<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();


require_once dirname(__DIR__) . '/app/controllers/PessoaController.php';
$pessoaController = new PessoaController();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Home'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="card shadow-sm mb-2" style="min-height: 320px; height: auto;">
                            <div class="card-header bg-primary text-white px-2 py-1  card-background">Aniversariantes - <?php echo date('d/m') ?></div>
                            <div class="card-body p-1 bg-image" style="background-image: url('img/bg_cake.png'); ">
                                <div class="table-responsive mb-2">
                                    <table class="table table-striped table-bordered mb-0 custom_table">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Tipo</th>
                                                <th>Município/UF</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $aniversariantes = $pessoaController->BuscarAniversariante(date('m'), date('d'));

                                            if ($aniversariantes['status'] == 'success') {
                                                $totalAniversariantes = count($aniversariantes['dados']);
                                                $linhasPorPagina = 6;
                                                $totalPaginas = ceil($totalAniversariantes / $linhasPorPagina);

                                                $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                                                if ($paginaAtual < 1) {
                                                    $paginaAtual = 1;
                                                } elseif ($paginaAtual > $totalPaginas) {
                                                    $paginaAtual = $totalPaginas;
                                                }

                                                $indiceInicial = ($paginaAtual - 1) * $linhasPorPagina;
                                                $aniversariantesParaExibir = array_slice($aniversariantes['dados'], $indiceInicial, $linhasPorPagina);

                                                if (!empty($aniversariantesParaExibir)) {
                                                    foreach ($aniversariantesParaExibir as $pessoa) {
                                                        echo '<tr>';
                                                        echo '<td><a href="editar-pessoa.php?id=' . $pessoa['pessoa_id'] . '">' . $pessoa['pessoa_nome'] . '</a></td>'; // Nome
                                                        echo '<td>' . $pessoa['pessoa_tipo_nome'] . '</td>'; // Tipo
                                                        echo '<td>' . $pessoa['pessoa_municipio'] . ' | ' . $pessoa['pessoa_estado'] . '</td>'; // Município/UF
                                                        echo '</tr>';
                                                    }
                                                } else {
                                                    echo '<tr><td colspan="3">Nenhum aniversariante encontrado.</td></tr>'; // Mensagem caso não haja aniversariantes
                                                }
                                            } else {
                                                echo '<tr><td colspan="3">Nenhum aniversariante encontrado.</td></tr>'; // Mensagem caso não haja aniversariantes
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <ul class="pagination mb-0" style="font-size: 0.7em;">
                                    <?php
                                    if (isset($totalPaginas)) {
                                        for ($i = 1; $i <= $totalPaginas; $i++) {
                                            echo '<li class="page-item ' . ($i == $paginaAtual ? 'active' : '') . '">';
                                            echo '<a class="page-link" style="font-size: 0.9em;" href="?pagina=' . $i . '">' . $i . '</a>';
                                            echo '</li>';
                                        }
                                    }

                                    ?>
                                </ul>



                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="card shadow-sm mb-2" style="height: 320px;">
                            <div class="card-header bg-primary text-white px-2 py-1  card-background">Postagens agendadas</div>
                            <div class="card-body p-2">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="card shadow-sm mb-2" style="height: 320px;">
                            <div class="card-header bg-primary text-white px-2 py-1  card-background">Agenda parlamentar</div>
                            <div class="card-body p-2">

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