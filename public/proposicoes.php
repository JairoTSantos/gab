<?php
$config = require dirname(__DIR__) . '/app/config/config.php';
$depConfig = $config['deputado'];

require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/ProposicaoController.php';
$proposicaoController = new ProposicaoController();

$ano = isset($_GET['ano']) ? $_GET['ano'] : date('Y');
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'PL';
$arquivada = isset($_GET['arquivada']) ? $_GET['arquivada'] : 0;
$autoria = isset($_GET['autoria']) ? $_GET['autoria'] : 1;




?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Proposições'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar() ?>
                <?php $layoutClass->cardDescription('<i class="fa-regular fa-file-lines"></i> Proposições do deputado', '
                <p class="card-text mb-2">Acompanhe aqui as proposições apresentadas pelo deputado.</p>
                <p class="card-text mb-0">Você pode visualizar tanto as proposições de autoria exclusiva do deputado quanto aquelas em que ele é coautor ou subscritor.</p>') ?>

                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2 ">
                            <div class="card-body p-0">
                                <nav class="navbar navbar-expand bg-body-tertiary p-0 ">
                                    <div class="container-fluid p-0">
                                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                            <ul class="navbar-nav me-auto mb-0 mb-lg-0">
                                                <li class="nav-item">
                                                    <a class="nav-link active p-1" aria-current="page" href="relatorio_proposicoes.php" target="_blank">
                                                        <button class="btn btn-success btn-sm" style="font-size: 0.850em;" id="btn_novo_tipo" type="button">
                                                            <i class="fa-solid fa-file-lines"></i> Gerar relatório
                                                        </button>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <form class="row g-2 form_custom mb-0" method="GET" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-1 col-3">
                                        <select class="form-select form-select-sm" name="ano" required>
                                            <?php
                                            for ($i = $depConfig['ano_primeira_legislatura']; $i < date('Y') + 1; $i++) {
                                                if ($i == $ano) {
                                                    echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                                } else {
                                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-9">
                                        <select class="form-select form-select-sm" name="tipo" required>
                                            <option value="PL" <?php echo ($tipo === 'PL') ? 'selected' : ''; ?>>Projeto de Lei</option>
                                            <option value="REQ" <?php echo ($tipo === 'REQ') ? 'selected' : ''; ?>>Requerimento</option>
                                            <option value="PEC" <?php echo ($tipo === 'PEC') ? 'selected' : ''; ?>>Proposta de Emenda a Constituição</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-5">
                                        <select class="form-select form-select-sm" name="arquivada" required>
                                            <option value="0" <?php echo ($arquivada == 0) ? 'selected' : ''; ?>>Em tramitação</option>
                                            <option value="1" <?php echo ($arquivada == 1) ? 'selected' : ''; ?>>Arquivadas</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-5">
                                        <select class="form-select form-select-sm" name="autoria" required>
                                            <option value="1" <?php echo ($autoria == 1) ? 'selected' : ''; ?>>Autoria única</option>
                                            <option value="0" <?php echo ($autoria == 0) ? 'selected' : ''; ?>>Coautoria ou subscrição</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-1">
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $proposicoes = [];
                $result = $proposicaoController->ListarProposicoesDeputado($ano, $tipo, $arquivada);



                if ($result['status'] == 'success') {
                    foreach ($result['dados'] as $proposicao) {
                        // Cria um array com informações necessárias para o filtro
                        $tempProposicao = [
                            'Título' => '<a href="det_proposicao.php?proposicao=' . $proposicao['proposicao_id'] . '">' . $proposicao['proposicao_titulo'] . '</a>',
                            'Ementa' => $proposicao['proposicao_ementa'],
                            'Autoria' => $proposicao['proposicao_autoria'] ? '1' : '0'
                        ];

                        // Adiciona o array temporário ao array principal
                        $proposicoes[] = $tempProposicao;
                    }

                    // Verifique se $autoria não é nulo ou não está vazio antes de filtrar
                    if (isset($autoria) && $autoria != '') {
                        $proposicoes = array_filter($proposicoes, function ($item) use ($autoria) {
                            return $item['Autoria'] === ($autoria == 1 ? '1' : '0');
                        });
                    }

                    // Prepare um novo array apenas com as colunas que você deseja mostrar
                    $tabelaProposicoes = array_map(function ($item) {
                        return [
                            'Título' => $item['Título'],
                            'Ementa' => $item['Ementa']
                            // Você pode adicionar outras colunas que deseja exibir aqui
                        ];
                    }, $proposicoes);

                    echo $layoutClass->criarTabela($tabelaProposicoes);
                } else if ($result['status'] == 'error') {
                    echo $layoutClass->criarTabela([['Mensagem' => 'Erro interno do servidor.']]);
                } else {
                    echo $layoutClass->criarTabela([]);
                }
                ?>







            </div>
        </div>
    </div>
    </div>
</body>

</html>