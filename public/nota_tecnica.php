<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';
require_once dirname(__DIR__) . '/app/core/GetJson.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/NotaTecnicaController.php';
$notaTecnicaController = new NotaTecnicaController();

$proposicaoIdGet = $_GET['proposicao'];

$proposicao = $notaTecnicaController->BuscarNotaTecnica('nota_proposicao', $proposicaoIdGet);

$dadosJson = getJson('https://dadosabertos.camara.leg.br/api/v2/proposicoes/' . $proposicaoIdGet)['dados'];

if (empty($dadosJson)) {
    header('Location: home.php');
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Nota Técnica'); ?>
    <script src="https://cdn.tiny.cloud/1/9ebpappotq2edw5qf3ik3e6m9owenjuy9buc8ddrbngfw88f/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'anchor autolink charmap codesample emoticons image  link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image  media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            height: 600,
            language: 'pt_BR',
            setup: function(editor) {
                editor.on('init', function() {
                    editor.getBody().style.fontSize = '10pt';
                });
            }
        });
    </script>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar() ?>
                <?php $layoutClass->cardDescription('<i class="fa-solid fa-briefcase"></i> Nota Técnica', '<p class="card-text mb-0">Seção para criar e visualizar notas técnicas de proposições</p>') ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-2"><?php echo $dadosJson['siglaTipo'] . ' ' . $dadosJson['numero'] . '/' . $dadosJson['ano'] ?></h5>
                                <p class="card-text mb-2"> <?php echo $dadosJson['statusProposicao']['apreciacao'] ?></p>
                                <p class="card-text mb-3" style="font-size: 0.9em;"><em> <?php echo $dadosJson['ementa'] ?></em></p>
                                <p class="card-text mb-0" style="font-size: 0.9em;"><a href="<?php echo $dadosJson['urlInteiroTeor'] ?>" target="_blank"><i class="fa-regular fa-file-lines"></i> Ver inteiro teor </a> | <a href="imprimir_nota.php?proposicao=<?php echo $proposicaoIdGet ?>&imprimir=1" target="_blank"><i class="fa-solid fa-print"></i> Imprimir nota</a></p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <?php

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {
                                    $nota = [
                                        'nota_titulo' => $_POST['nota_titulo'],
                                        'nota_resumo' => $_POST['nota_resumo'],
                                        'nota_texto' => $_POST['nota_texto'],
                                        'nota_proposicao' => $proposicaoIdGet
                                    ];

                                    $resultado = $notaTecnicaController->NovaNotaTecnica($nota);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', 'Nota adicionada com sucesso!', 3);
                                        $proposicao = $notaTecnicaController->BuscarNotaTecnica('nota_proposicao', $proposicaoIdGet);
                                    } else if ($resultado['status'] === 'duplicated') {
                                        $layoutClass->alert('info', 'Essa nota já existe.', 3);
                                    } else if ($resultado['status'] === 'bad_request') {
                                        $layoutClass->alert('danger', 'Preencha todos os campos.', 3);
                                    } else if ($resultado['status'] === 'error') {
                                        $layoutClass->alert('danger', 'Erro ao adicionar a nota.', 3);
                                    }
                                }
                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_atualizar'])) {
                                    $nota = [
                                        'nota_titulo' => $_POST['nota_titulo'],
                                        'nota_resumo' => $_POST['nota_resumo'],
                                        'nota_texto' => $_POST['nota_texto']
                                    ];

                                    $resultado = $notaTecnicaController->AtualizarNotaTecnica($proposicaoIdGet, $nota);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', 'Nota atualizada com sucesso!', 3);
                                        $proposicao = $notaTecnicaController->BuscarNotaTecnica('nota_proposicao', $proposicaoIdGet);
                                    } else if ($resultado['status'] === 'error') {
                                        $layoutClass->alert('danger', 'Erro ao adicionar a nota.', 3);
                                    } else if ($resultado['status'] === 'bad_request') {
                                        $layoutClass->alert('danger', 'Preencha todos os campos.', 3);
                                    }
                                }

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {

                                    $resultado = $notaTecnicaController->ApagarNotaTecnica($proposicaoIdGet);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', 'Nota apagada com sucesso!', 3);
                                        $proposicao = $notaTecnicaController->BuscarNotaTecnica('nota_proposicao', $proposicaoIdGet);
                                    } else if ($resultado['status'] === 'error' || $resultado['status'] === 'bad_request') {
                                        $layoutClass->alert('danger', 'Erro ao apagar a nota.', 3);
                                    }
                                    
                                }

                                ?>
                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-6 col-12">
                                        <input type="text" class="form-control" name="nota_titulo" placeholder="Título" value="<?php echo isset($proposicao['dados']['nota_titulo']) ? $proposicao['dados']['nota_titulo'] : ''; ?>" required>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <input type="text" class="form-control" name="nota_resumo" placeholder="Resumo (Apelido, nome amigavel, resumo simples...)" value="<?php echo isset($proposicao['dados']['nota_resumo']) ? $proposicao['dados']['nota_resumo'] : ''; ?>" required>
                                    </div>

                                    <div class="col-md-12 col-12">
                                        <textarea class="form-control" name="nota_texto" placeholder="Texto da nota técnica"><?php echo isset($proposicao['dados']['nota_texto']) ? $proposicao['dados']['nota_texto'] : ''; ?></textarea>
                                    </div>
                                    <div class="col-md-4 col-6">

                                        <?php
                                        if (!isset($proposicao['dados']['nota_titulo'])) {
                                            echo '<button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>';
                                        } else {
                                            echo '<button type="submit" class="btn btn-primary btn-sm" name="btn_atualizar"><i class="fa-regular fa-floppy-disk"></i> Atualizar</button>';
                                        }
                                        ?>
                                        <button type="submit" class="btn btn-danger btn-sm" name="btn_apagar"><i class="fa-solid fa-trash-can"></i> Apagar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('button[name="btn_apagar"]').on('click', function(event) {
            const confirmacao = confirm("Tem certeza que deseja apagar esta nota?");
            if (!confirmacao) {
                event.preventDefault();
            }
        });

        function copiarLink(event, url) {
            event.preventDefault(); // Impede o comportamento padrão do link

            // Cria um elemento temporário para copiar a URL
            const tempInput = document.createElement('input');
            tempInput.value = url;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy'); // Copia o valor do input para a área de transferência
            document.body.removeChild(tempInput); // Remove o input temporário

            // Exibe o alerta informando que o link foi copiado
            alert('Link copiado!');
        }
    </script>
</body>

</html>