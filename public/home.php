<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();


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
                        <div class="card shadow-sm mb-2" style="height: 320px;">
                            <div class="card-header bg-primary text-white px-2 py-1  card-background">Aniversariantes</div>
                            <div class="card-body p-2">
                                <?php

                                $dados = [
                                    [
                                        'nome' => 'João Silva',
                                        'municipio' => 'São Paulo',
                                        'estado' => 'SP',
                                        'tipo' => 'Residencial',
                                    ],
                                    [
                                        'nome' => 'Maria Oliveira',
                                        'municipio' => 'Rio de Janeiro',
                                        'estado' => 'RJ',
                                        'tipo' => 'Comercial',
                                    ],

                                    [
                                        'nome' => 'Maria Oliveira',
                                        'municipio' => 'Rio de Janeiro',
                                        'estado' => 'RJ',
                                        'tipo' => 'Comercial',
                                    ],
                                    [
                                        'nome' => 'Carlos Souza',
                                        'municipio' => 'Belo Horizonte',
                                        'estado' => 'MG',
                                        'tipo' => 'Residencial',
                                    ],
                                    [
                                        'nome' => 'Ana Costa',
                                        'municipio' => 'Porto Alegre',
                                        'estado' => 'RS',
                                        'tipo' => 'Industrial',
                                    ],
                                    [
                                        'nome' => 'Pedro Santos',
                                        'municipio' => 'Salvador',
                                        'estado' => 'BA',
                                        'tipo' => 'Residencial',
                                    ],
                                ];

                                echo $layoutClass->criarTabela($dados);


                                ?>

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