<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/PessoaController.php';
$pessoaController = new PessoaController();

require_once dirname(__DIR__) . '/app/core/GetJson.php';


$dia = isset($_GET['dia']) ? (int)$_GET['dia'] : date('d');
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('m');


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Aniversários'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar() ?>
                <?php $layoutClass->cardDescription('<i class="fa-solid fa-cake-candles"></i> Aniversáriantes - Pessoas', '<p class="card-text mb-0">Pessoas de interesse do mandato.</p>') ?>
                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <form class="row g-2 form_custom mb-0" method="GET" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-1 col-6">
                                        <select class="form-select form-select-sm" name="dia" required>
                                            <option value="00" <?php echo $dia == 0 ? 'selected' : ''; ?>>Todos os dias</option>
                                            <?php for ($i = 1; $i <= 31; $i++): ?>
                                                <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php echo $dia == $i ? 'selected' : ''; ?>>
                                                    <?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" name="mes" required>
                                            <?php
                                            $meses = [
                                                '01' => 'Janeiro',
                                                '02' => 'Fevereiro',
                                                '03' => 'Março',
                                                '04' => 'Abril',
                                                '05' => 'Maio',
                                                '06' => 'Junho',
                                                '07' => 'Julho',
                                                '08' => 'Agosto',
                                                '09' => 'Setembro',
                                                '10' => 'Outubro',
                                                '11' => 'Novembro',
                                                '12' => 'Dezembro'
                                            ];
                                            ?>
                                            <?php foreach ($meses as $valor => $nome): ?>
                                                <option value="<?php echo $valor; ?>" <?php echo $mes == (int)$valor ? 'selected' : ''; ?>>
                                                    <?php echo $nome; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-magnifying-glass"></i></button>
                                        <a type="button" href="aniversarios.php" class="btn btn-success btn-sm"><i class="fa-solid fa-calendar-day"></i> Hoje</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php

                if ($dia == 0) {
                    $buscaPessoas = $pessoaController->BuscarAniversariante($mes);
                } else {
                    $buscaPessoas = $pessoaController->BuscarAniversariante($mes, $dia);
                }

                $tabelaPessoas = [];

                if ($buscaPessoas['status'] == 'success' && $buscaPessoas['status'] != 'empty') {
                    foreach ($buscaPessoas['dados'] as $pessoa) {
                        $tabelaPessoas[] = [
                            'Aniversário' =>  date('d/m', strtotime($pessoa['pessoa_aniversario'])),
                            'Nome' => '<a href="editar-pessoa.php?id=' . $pessoa['pessoa_id'] . '">' . $pessoa['pessoa_nome'] . '</a>',
                            'Email' =>  $pessoa['pessoa_email'],
                            'Facebook' =>  $pessoa['pessoa_facebook'],
                            'Instagram' =>  $pessoa['pessoa_instagram'],
                            'Twitter' =>  $pessoa['pessoa_x'],
                            'Telefone' =>  $pessoa['pessoa_telefone']
                        ];
                    }
                    echo $layoutClass->criarTabela($tabelaPessoas);
                } else if ($buscaPessoas['status'] == 'error') {
                    echo $layoutClass->criarTabela([['Mensagem' => 'Erro interno do servidor.']]);
                } else {
                    echo $layoutClass->criarTabela([]);
                }

                ?>

            </div>
        </div>
    </div>
</body>

</html>