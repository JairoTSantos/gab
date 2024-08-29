<?php
require_once __DIR__ . '/includes/verificaLogado.php';
require_once __DIR__ . '/includes/layout.php';
require_once dirname(__DIR__) . '/app/controllers/ComissoesController.php';

$comissoesController = new ComissoesController();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php montarHeader('Pauta'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php include 'includes/side_menu.php' ?>
        <div id="page-content-wrapper">
            <?php include 'includes/top_menu.php' ?>
            <div class="container-fluid p-2">
                <?php navBar(); ?>
                <?php cardDescription('<i class="fa-regular fa-rectangle-list"></i> Pauta da reunião', '<p class="card-text mb-0">Veja todas os itens da pauta para essa reunião</p>'); ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body p-2">
                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                    <?php
                                    $itens = $comissoesController->BuscarPauta($id);
                                    $flag_apensado = '';
                                    if (!empty($itens)) {
                                        foreach ($itens as $item) {
                                            
                                            echo '<div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" style="font-size:0.450em" type="button" data-bs-toggle="collapse" data-bs-target="#pauta' . $item['pauta_ordem'] . '" aria-expanded="true" aria-controls="pauta' . $item['pauta_ordem'] . '">
                                                        <b>' . $item['pauta_ordem'] . '</b> &nbsp;- ' . $item['pauta_titulo'];

                                                        if (isset($item['proposicao'])) {
                                                            if(!empty($item['proposicao']['apensados_do_deputado'])){
                                                                echo ' | &nbsp;<i class="fa-solid fa-circle-check"></i>&nbsp; <b>Existe apensado</b>';
                                                            }
                                                        }



                                            if (isset($item['proposicao_relator']) && $item['proposicao_relator']['relator_flag']) {
                                                echo ' | &nbsp;<i class="fa-solid fa-circle-check"></i>&nbsp; <b>Relatoria</b>';
                                            }

                                            echo '</button>
                                                </h2>
                                                <div id="pauta' . $item['pauta_ordem'] . '" class="accordion-collapse collapse">
                                                    <div class="accordion-body">
                                                       <!-- <p style="font-size:1em" class="mb-3">&#8594; ' . $item['pauta_regime'] . '</p>-->
                                                       <div class="border px-4 py-3 mb-3" style="background-color:#f5fff5">
                                                            <p style="font-size:0.900em" class="mb-1">&#9864; ' . $item['proposicao_em_votacao']['proposicao_titulo'] . '</p>
                                                            <p style="font-size:0.900em" class="mb-1">&#9864; <em>' . $item['proposicao_em_votacao']['proposicao_ementa'] . '</em></p>
                                                            <p style="font-size:0.900em" class="mb-1">&#9864; Relator: ' . (!empty($item['proposicao_relator']) ? $item['proposicao_relator']['relator_nome'] . '/' . $item['proposicao_relator']['relator_partido'] : 'Sem relator') . '</p>
                                                            ' . (!empty($item['pauta_situacao']) ? '<p style="font-size:0.900em" class="mb-0"> &#9864; ' . $item['pauta_situacao'] . '</p>' : '') . '
                                                        </div>';


                                            if (isset($item['proposicao'])) {
                                                echo '
                                                            <div class="border px-4 py-3 mb-3" style="background-color:#e8edfc">
                                                                <p style="font-size:0.900em" class="mb-1">&#9864; ' . $item['proposicao']['proposicao_titulo'] . '</p>
                                                                <p style="font-size:0.900em" class="mb-2">&#9864; <em>' . $item['proposicao']['proposicao_ementa'] . '</em></p>
                                                                <p style="font-size:0.900em" class="mb-0"><a target="_blank" href="https://www.camara.leg.br/proposicoesWeb/fichadetramitacao?idProposicao=' . $item['proposicao']['proposicao_id'] . '"> Ver ficha de tramitação</a></p>
                                                            </div>
                                                            
                                                            ';

                                                if(!empty($item['proposicao']['apensados_do_deputado'])){
                                                   echo '<div class="border px-4 py-3 mb-3" style="background-color:#b5e8cb">
                                                            <h4 style="font-size:1em"><b>Apensados do deputado</b></h4>
                                                            <p style="font-size:0.900em" class="mb-1">&#9864; ' . $item['proposicao']['apensados_do_deputado'][0]['apensado_titulo']. '</p>
                                                             <p style="font-size:0.900em" class="mb-1">&#9864; ' . $item['proposicao']['apensados_do_deputado'][0]['apensado_ementa']. '</p>
                                                              <p style="font-size:0.900em" class="mb-0"><a target="_blank" href="https://www.camara.leg.br/proposicoesWeb/fichadetramitacao?idProposicao=' . $item['proposicao']['apensados_do_deputado'][0]['apensado_id'] . '"> Ver ficha de tramitação</a></p>
                                                   
                                                   
                                                         </div>';
                                                }

                                                
                                            }


                                            echo '</div>
                                                </div>
                                            </div>';
                                        }
                                    } else {
                                        echo '<p class="card-text">Nenhuma pauta encontrada</p>';
                                    }



                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>