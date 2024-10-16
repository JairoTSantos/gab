<?php

require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/OrgaoController.php';
$orgaoController = new OrgaoController();

require_once dirname(__DIR__) . '/app/controllers/OrgaoTipoController.php';
$orgaoTipoController = new OrgaoTipoController();

require_once dirname(__DIR__) . '/app/controllers/PessoaController.php';
$pessoaController = new PessoaController();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$buscarOrgao = $orgaoController->BuscarOrgaos('orgao_id', $id);

if ($buscarOrgao['status'] == 'empty' || $buscarOrgao['status'] == 'error') {
    header('Location: orgaos.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Editar órgão') ?>
</head>


<body class="bg-secondary">
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar(true, 'orgaos.php') ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm mb-2 card-background">
                            <div class="card-body p-2">
                                <div class="row">

                                    <div class="col-12 col-md-11 mt-2 ">
                                        <h5 class="card-title"><?php echo $buscarOrgao['dados']['orgao_nome']; ?></h5>
                                        <p class="card-text mb-2"><i class="fa-solid fa-envelope"></i> <?php echo $buscarOrgao['dados']['orgao_email']; ?></p>
                                        <p class="card-text mb-2"><i class="fa-solid fa-mobile-screen"></i> <?php echo $buscarOrgao['dados']['orgao_telefone'] ? $buscarOrgao['dados']['orgao_telefone'] : 'Sem telefone'; ?></p>
                                        <p class="card-text mb-2"><i class="fa-solid fa-map-location-dot"></i> <?php echo $buscarOrgao['dados']['orgao_endereco'] ? $buscarOrgao['dados']['orgao_endereco'] . ' ' . $buscarOrgao['dados']['orgao_bairro'] . ' | ' . $buscarOrgao['dados']['orgao_municipio'] . '/' . $buscarOrgao['dados']['orgao_estado'] : 'Sem endereço'; ?></p>
                                        <p class="card-text mb-2"><i class="fa-solid fa-globe"></i> <?php echo $buscarOrgao['dados']['orgao_site'] ? $buscarOrgao['dados']['orgao_site'] : 'Sem site ou redes sociais'; ?></p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 ">
                        <div class="card shadow-sm mb-2 ">
                            <div class="card-body p-0">
                                <nav class="navbar navbar-expand bg-body-tertiary p-0">
                                    <div class="container-fluid p-0 ">
                                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                            <ul class="navbar-nav me-auto mb-0 mb-lg-0 ">
                                                <li class="nav-item">
                                                    <a class="nav-link active" aria-current="page" href="<?php echo $config['app']['url'] ?>/orgaos-tipos">
                                                        <button class="btn btn-outline-success btn-sm" style="font-size: 0.850em;" id="btn_novo_tipo" type="button">
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

                                    $resultado = $orgaoController->AtualizarOrgao($id, $orgao);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                        echo '<script>
                                        setTimeout(function(){
                                            window.location.href = "editar-orgao.php?id=' . $id . '";
                                        }, 1000);
                                    </script>';
                                    } else if ($resultado['status'] === 'error' || $resultado['status'] === 'bad_request') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                                    $resultado = $orgaoController->ApagarOrgao($id);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                        echo '<script>
                                                    setTimeout(function(){
                                                        window.location.href = "orgaos.php";
                                                    }, 500);
                                                </script>';
                                    } elseif ($resultado['status'] === 'error' || $resultado['status'] === 'invalid_id' || $resultado['status'] === 'delete_conflict') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }

                                ?>
                                <form class="row g-2 form_custom " id="form_novo" method="POST" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-5 col-12">
                                        <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome" value="<?php echo $buscarOrgao['dados']['orgao_nome'] ?>" required>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <input type="email" class="form-control form-control-sm" name="email" placeholder="Email" value="<?php echo $buscarOrgao['dados']['orgao_email'] ?>" required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="telefone" placeholder="Telefone (somente números)" value="<?php echo $buscarOrgao['dados']['orgao_telefone'] ?>" maxlength="11">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <input type="text" class="form-control form-control-sm" name="endereco" placeholder="Endereço " value="<?php echo $buscarOrgao['dados']['orgao_endereco'] ?>">
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input type="text" class="form-control form-control-sm" name="cep" placeholder="CEP (somente números)" value="<?php echo $buscarOrgao['dados']['orgao_cep'] ?>" maxlength="8">
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="bairro" placeholder="Bairro" value="<?php echo $buscarOrgao['dados']['orgao_bairro'] ?>">
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
                                            $buscaTipos = $orgaoTipoController->ListarTipoOrgaos();
                                            if ($buscaTipos['status'] === 'success') {
                                                foreach ($buscaTipos['dados'] as $tipos) {
                                                    if ($tipos['orgao_tipo_id'] == $buscarOrgao['dados']['orgao_tipo']) {
                                                        echo '<option value="' . $tipos['orgao_tipo_id'] . '" selected>' . $tipos['orgao_tipo_nome'] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $tipos['orgao_tipo_id'] . '">' . $tipos['orgao_tipo_nome'] . '</option>';
                                                    }
                                                }
                                            } else if ($buscaTipos['status'] === 'empty') {
                                                echo ' <option value="1000" selected>Tipo não informado</option>';
                                            } else {
                                                echo ' <option value="1000" selected>Tipos não carregados</option>';
                                            }

                                            ?>
                                            <option value="+">Novo tipo + </option>
                                        </select>
                                    </div>
                                    <div class="col-md-9 col-12">
                                        <input type="text" class="form-control form-control-sm" name="site" placeholder="Site ou rede sociais" value="<?php echo $buscarOrgao['dados']['orgao_site'] ?>">
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <textarea class="form-control form-control-sm" name="informacoes" rows="5" placeholder="Informações importantes desse órgão"><?php echo $buscarOrgao['dados']['orgao_informacoes'] ?></textarea>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>

                                        <button type="submit" class="btn btn-danger btn-sm" name="btn_apagar"><i class="fa-solid fa-trash"></i> Apagar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php
                $pessoas = $pessoaController->BuscarPessoa('pessoa_orgao', $id);
                $tabela = [];

                if ($pessoas['status'] == 'success') {
                    echo '<div class="row">
                                <div class="col-12">
                                    <div class="card shadow-sm mb-2">
                                        <div class="card-body p-2">
                                            <p class="card-text">Pessoas que fazem parte desse órgão</p>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    foreach ($pessoas['dados'] as $pessoa) {
                        $tabela[] = [
                            'Nome' => '<a href="editar-pessoa.php?id=' . $pessoa['pessoa_id'] . '">' . $pessoa['pessoa_nome'] . '</a>',
                            'Email' => $pessoa['pessoa_email'],
                            'Aniversário' => date('d/m/Y', strtotime($pessoa['pessoa_aniversario'])),
                            'Telefone' => $pessoa['pessoa_telefone']
                        ];
                    }
                    echo $layoutClass->criarTabela($tabela);
                }

                ?>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            carregarEstados();
        });

        function carregarEstados() {
            $.getJSON('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome', function(data) {
                const selectEstado = $('#estado');
                selectEstado.empty();
                selectEstado.append('<option value="" selected>UF</option>');
                data.forEach(estado => {
                    if (estado.sigla === "<?php echo $buscarOrgao['dados']['orgao_estado'] ?>") {
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
                    if (municipio.nome === "<?php echo $buscarOrgao['dados']['orgao_municipio'] ?>") {
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
                    window.location.href = "orgaos-tipos.php";
                }
            }
        });

        $('button[name="btn_apagar"]').on('click', function(event) {
            const confirmacao = confirm("Tem certeza que deseja apagar este órgao?");
            if (!confirmacao) {
                event.preventDefault();
            }
        });

        $('button[name="btn_salvar"]').on('click', function(event) {
            const confirmacao = confirm("Tem certeza que deseja atualizar este órgao?");
            if (!confirmacao) {
                event.preventDefault();
            }
        });

        $('#btn_novo_tipo').click(function() {
            if (window.confirm("Você realmente deseja inserir um novo tipo?")) {
                window.location.href = "orgaos-tipos.php";
            } else {
                return false;
            }
        });
    </script>
</body>

</html>