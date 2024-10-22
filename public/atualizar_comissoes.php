<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/ComissoesController.php';
$comissoesController = new ComissoesController();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Atualizar comissões'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php
                $layoutClass->cardDescription(
                    '<i class="fa-solid fa-building"></i> Atualização de Comissões',
                    '<p class="card-text mb-0">Nesta seção, é possível atualizar as comissões da Câmara e as comissões nas quais o deputado é membro. Selecione a opção desejada no menu e clique em "Atualizar".</p>'
                )
                ?>
                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <?php
                                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                    $tipo = $_POST['tipo'];
                                    if ($tipo == 1) {
                                        $result = $comissoesController->AtualizarComissoes();
                                        if ($result['status'] == 'success') {
                                            $layoutClass->alert('success', $result['message'], 3);
                                        } else {
                                            $layoutClass->alert('danger', $result['message'], 3);
                                        }
                                    } else if ($tipo == 2) {
                                        $result = $comissoesController->AtualizarComissoesDep();
                                        if ($result['status'] == 'success') {
                                            $layoutClass->alert('success', $result['message'], 3);
                                        } else {
                                            $layoutClass->alert('danger', $result['message'], 3);
                                        }
                                    }
                                }
                                ?>
                                <form class="row g-2 form_custom mb-0" method="POST" enctype="application/x-www-form-urlencoded">
                                    <input type="hidden" name="tipo" value="<?php echo $tipo_comissao ?>" />
                                    <div class="col-md-2 col-8">
                                        <select class="form-select form-select-sm" name="tipo" required>
                                            <option value="1">Todas as comissões</option>
                                            <option value="2">Comissões do deputado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-4">
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-arrows-rotate"></i> Atualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>