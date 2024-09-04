<?php

require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/app/controllers/OrgaoController.php';

$orgaoController = new OrgaoController();

$id = $_GET['id'];

$buscaOrgao = $orgaoController->buscarOrgao('orgao_id', $id);

if ($buscaOrgao['status'] === 'empty') {
    header("Location: ../orgaos");
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php montarHeader('Órgãos'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php include 'includes/side_menu.php' ?>
        <div id="page-content-wrapper">
            <?php include 'includes/top_menu.php' ?>
            <div class="container-fluid p-2">
                <?php navBar(true, '../orgaos'); ?>
                <?php cardDescription('<i class="fa-regular fa-building"></i> Editar Órgão', '<p class="card-text mb-0">Por favor, preencha os dados do órgão ou instituição para atualiza-lo no sistema.</p><p class="card-text mb-0">Os campos obrigatórios são:</p><p class="card-text mt-2 mb-0"><ul><li>Nome</li><li>Email</li><li>Município</li><li>Estado</li></ul></p>'); ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-0">
                                <nav class="navbar navbar-expand bg-body-tertiary p-0">
                                    <div class="container-fluid p-0">
                                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                            <ul class="navbar-nav me-auto mb-0 mb-lg-0">
                                                <li class="nav-item">
                                                    <a class="nav-link active" aria-current="page" href="<?php echo $config['app']['url'] ?>/orgaos-tipos">
                                                        <button class="btn btn-outline-success btn-sm" style="font-size: 0.850em;" type="button">
                                                            <i class="fa-solid fa-circle-plus"></i> Novo tipo
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
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">

                                <?php

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {
                                    $orgao = [
                                        'orgao_nome' => $_POST['nome'],
                                        'orgao_email' => $_POST['email'],
                                        'orgao_telefone' => $_POST['telefone'],
                                        'orgao_endereco' => $_POST['endereco'],
                                        'orgao_cep' => $_POST['cep'],
                                        'orgao_bairro' => $_POST['bairro'],
                                        'orgao_estado' => $_POST['estado'],
                                        'orgao_municipio' => $_POST['municipio'],
                                        'orgao_tipo' => $_POST['tipo'],
                                        'orgao_site' => $_POST['site'],
                                        'orgao_informacoes' => $_POST['informacoes']
                                    ];

                                    $resultado = $orgaoController->atualizarOrgao($id, $orgao);

                                    if ($resultado['status'] === 'success') {
                                        alert('success', $resultado['message'], 3);
                                        $buscaOrgao = $orgaoController->buscarOrgao('orgao_id', $id);
                                    } elseif ($resultado['status'] === 'duplicated' || $resultado['status'] === 'invalid_email' || $resultado['status'] === 'bad_request') {
                                        alert('info', $resultado['message'], 3);
                                    } elseif ($resultado['status'] === 'error') {
                                        alert('danger', $resultado['message'], 3);
                                    }
                                }

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                                    $resultado = $orgaoController->apagarOrgao($id);

                                    if ($resultado['status'] === 'success') {
                                        alert('success', $resultado['message'], 3);
                                        echo '<script>
                                                setTimeout(function(){
                                                    window.location.href = "../orgaos";
                                                }, 1000);
                                            </script>';
                                    } elseif ($resultado['status'] === 'error' || $resultado['status'] === 'invalid_id' || $resultado['status'] === 'delete_conflict') {
                                        alert('danger', $resultado['message'], 3);
                                    }
                                }

                                ?>

                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-5 col-12">
                                        <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome" value="<?php echo $buscaOrgao['dados']['orgao_nome'] ?>" required>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <input type="email" class="form-control form-control-sm" name="email" placeholder="Email" value="<?php echo $buscaOrgao['dados']['orgao_email'] ?>" required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="telefone" placeholder="Telefone" value="<?php echo $buscaOrgao['dados']['orgao_telefone'] ?>" maxlength="11">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <input type="text" class="form-control form-control-sm" name="endereco" placeholder="Endereço" value="<?php echo $buscaOrgao['dados']['orgao_endereco'] ?>">
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input type="text" class="form-control form-control-sm" name="cep" placeholder="CEP" maxlength="8" value="<?php echo $buscaOrgao['dados']['orgao_cep'] ?>">
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="bairro" placeholder="Bairro" value="<?php echo $buscaOrgao['dados']['orgao_bairro'] ?>">
                                    </div>
                                    <div class="col-md-1 col-6">
                                        <select class="form-select form-select-sm" id="estado" name="estado" required>
                                            <option value="" selected>UF</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" id="municipio" name="municipio" required>
                                            <option value="" selected>Município</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <select class="form-select form-select-sm" id="tipo" name="tipo" required>
                                            <?php
                                            $buscaTipo = $orgaoController->listarTiposOrgaos();
                                            if ($buscaTipo['status'] === 'success') {
                                                foreach ($buscaTipo['dados'] as $tipo) {
                                                    if ($tipo['orgao_tipo_id'] === $buscaOrgao['dados']['orgao_tipo']) {
                                                        echo '<option value="' . $tipo['orgao_tipo_id'] . '" selected>' . $tipo['orgao_tipo_nome'] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $tipo['orgao_tipo_id'] . '">' . $tipo['orgao_tipo_nome'] . '</option>';
                                                    }
                                                }
                                            } else {
                                                echo '<option value="1000" selected>Tipos não carregados</option>';
                                            }
                                            ?>
                                            <option value="+">Novo tipo + </option>
                                        </select>
                                    </div>
                                    <div class="col-md-9 col-12">
                                        <input type="text" class="form-control form-control-sm" name="site" placeholder="Site ou rede sociais" value="<?php echo $buscaOrgao['dados']['orgao_site'] ?>">
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <textarea class="form-control form-control-sm" name="informacoes" rows="5" placeholder="Informações importantes desse órgão"><?php echo $buscaOrgao['dados']['orgao_informacoes'] ?></textarea>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <button type="submit" class="btn btn-success btn-sm" name="btn_salvar" id="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
                                        <button type="submit" class="btn btn-danger btn-sm" name="btn_apagar" id="btn_apagar"><i class="fa-solid fa-trash"></i> Apagar</button>
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
        $(document).ready(function() {
            carregarEstados();

            $("#btn_salvar").click(function(event) {
                const confirmacao = confirm("Tem certeza que deseja atualizar este órgão?");
                if (!confirmacao) {
                    event.preventDefault();
                }
            });

            $("#btn_apagar").click(function(event) {
                const confirmacao = confirm("Tem certeza que deseja apagar este órgão?");
                if (!confirmacao) {
                    event.preventDefault();
                }
            });

        });

        function carregarEstados() {
            $.getJSON('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome', function(data) {
                const selectEstado = $('#estado');
                selectEstado.empty();
                selectEstado.append('<option value="" selected>UF</option>');
                data.forEach(estado => {
                    if (estado.sigla === "<?php echo $buscaOrgao['dados']['orgao_estado'] ?>") {
                        setTimeout(function() {
                            selectEstado.append(`<option value="${estado.sigla}" selected>${estado.sigla}</option>`).change();
                        }, 500);

                    } else {
                        setTimeout(function() {
                            selectEstado.append(`<option value="${estado.sigla}">${estado.sigla}</option>`);
                        }, 500);
                    }
                });
            });
        }

        function carregarMunicipios(estadoId) {
            $.getJSON(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${estadoId}/municipios?orderBy=nome`, function(data) {
                const selectMunicipio = $('#municipio');
                selectMunicipio.empty();
                selectMunicipio.append('<option value="" selected>Município</option>');
                data.forEach(municipio => {
                    if (municipio.nome === "<?php echo $buscaOrgao['dados']['orgao_municipio'] ?>") {
                        selectMunicipio.append(`<option value="${municipio.nome}" selected>${municipio.nome}</option>`);
                    } else {
                        selectMunicipio.append(`<option value="${municipio.nome}">${municipio.nome}</option>`);
                    }
                });
            });
        }


        $('#estado').change(function() {
            const estadoId = $(this).val();
            if (estadoId) {
                $('#municipio').empty().append('<option value="">Aguarde...</option>');
                carregarMunicipios(estadoId);
            } else {
                $('#municipio').empty().append('<option value="" selected>Município</option>');
            }
        });


        $('#tipo').change(function() {
            if ($('#tipo').val() == '+') {
                if (window.confirm("Você realmente deseja inserir um novo tipo?")) {
                    window.location.href = "<?php echo $config['app']['url'] ?>/orgaos-tipos";
                }
            }
        });

        $('#btn_novo_tipo').click(function() {
            if (window.confirm("Você realmente deseja inserir um novo tipo?")) {
                window.location.href = "./orgaos-tipos";
            } else {
                return false;
            }
        });
    </script>
</body>

</html>