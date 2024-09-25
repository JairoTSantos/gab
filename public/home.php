<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();


require_once dirname(__DIR__) . '/app/controllers/PessoaController.php';
$pessoaController = new PessoaController();


require_once dirname(__DIR__) . '/app/controllers/PostagemController.php';
$postagemController = new PostagemController();

$paginaPostagem = isset($_GET['paginaPostagem']) ? (int)$_GET['paginaPostagem'] : 1;
$paginaAniversariante = isset($_GET['paginaAniversariante']) ? (int)$_GET['paginaAniversariante'] : 1;

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
                        <div class="card shadow-sm mb-2 card_home">
                            <div class="card-header bg-primary text-white px-2 py-1 card-background">Agenda parlamentar</div>
                            <div class="card-body p-1 bg-image" style="background-image: url('img/bg_agenda.png'); ">
                                <div class="table-responsive mb-2">

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="card shadow-sm mb-2  card_home">
                            <div class="card-header bg-primary text-white px-2 py-1 card-background">Aniversariantes</div>
                            <div class="card-body p-2 bg-image" style="background-image: url('img/bg_cake.png'); ">
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

                                            $totalPaginasAniversariante = 5;
                                            $totalPaginas = 0;
                                            $offsetAniversariante = ($paginaAniversariante - 1) * $totalPaginasAniversariante;

                                            if ($aniversariantes['status'] == 'success') {
                                                $totalAniversariantes = count($aniversariantes['dados']);
                                                $totalPaginas = ceil($totalAniversariantes / $totalPaginasAniversariante); // Total de páginas
                                                foreach (array_slice($aniversariantes['dados'], $offsetAniversariante, $totalPaginasAniversariante) as $pessoa) {
                                                    echo '<tr>';
                                                    echo '<td><a href="editar-pessoa.php?id=' . $pessoa['pessoa_id'] . '">' . htmlspecialchars($pessoa['pessoa_nome']) . '</a></td>';
                                                    echo '<td>' . htmlspecialchars($pessoa['pessoa_tipo_nome']) . '</td>';
                                                    echo '<td>' . htmlspecialchars($pessoa['pessoa_municipio']) . ' | ' . htmlspecialchars($pessoa['pessoa_estado']) . '</td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="3"><b>Nenhum aniversariante para hoje</b></td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <ul class="pagination mb-0" style="font-size: 0.8em;">
                                    <?php
                                    if ($totalPaginas > 1) {
                                        for ($i = 1; $i <= $totalPaginas; $i++) {
                                            $activeClass = ($i === $paginaAniversariante) ? 'active' : '';
                                            echo '<li class="page-item ' . $activeClass . '"><a class="page-link"  style="font-size: 0.8em" href="?paginaAniversariante=' . $i . '&paginaPostagem=' . $paginaPostagem . '">' . $i . '</a></li>';
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="card shadow-sm mb-2 card_home">
                            <div class="card-header bg-primary text-white px-2 py-1 card-background">Postagens</div>
                            <div class="card-body p-1 bg-image" style="background-image: url('img/bg_social.png'); ">
                                <div class="table-responsive mb-2">
                                    <table class="table table-striped table-bordered mb-0 custom_table">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Midia</th>
                                                <th>Situação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $postagens = $postagemController->BuscarPostagemdoDia(date('m'), date('d'));

                                            $totalPaginasPostagem = 5;
                                            $totalPaginas = 0;
                                            $offsetPostagem = ($paginaPostagem - 1) * $totalPaginasPostagem;

                                            if ($postagens['status'] == 'success') {
                                                // Número de itens por página
                                                $totalPostagens = count($postagens['dados']); // Total de postagens
                                                $totalPaginas = ceil($totalPostagens / $totalPaginasPostagem); // Total de páginas



                                                foreach (array_slice($postagens['dados'], $offsetPostagem, $totalPaginasPostagem) as $postagem) {
                                                    echo '<tr>';
                                                    echo '<td><b><a href="editar-postagem.php?id=' . $postagem['postagem_id'] . '">' . htmlspecialchars($postagem['postagem_titulo']) . '</b></a></td>';
                                                    echo '<td>' . htmlspecialchars($postagem['postagem_midias']) . '</td>';
                                                    echo '<td><b>' . htmlspecialchars($postagem['postagem_status_nome']) . '</b></td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="3"><b>Nenhuma postagem para hoje</b></td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <ul class="pagination mb-0" style="font-size: 0.8em;">
                                    <?php
                                    if ($totalPaginas > 1) {
                                        for ($i = 1; $i <= $totalPaginas; $i++) {
                                            $activeClass = ($i === $paginaPostagem) ? 'active' : ''; // Classe ativa na página atual
                                            echo '<li class="page-item ' . $activeClass . '"><a class="page-link"  style="font-size: 0.8em;" href="?paginaPostagem=' . $i . '&paginaAniversariante=' . $paginaAniversariante . '">' . $i . '</a></li>';
                                        }
                                    }
                                    ?>
                                </ul>
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