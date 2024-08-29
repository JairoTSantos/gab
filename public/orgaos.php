<?php

require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/app/controllers/OrgaoController.php';

$orgaoController = new OrgaoController();

$itens = isset($_GET['itens']) ? (int)$_GET['itens'] : 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$ordenarPor = isset($_GET['ordenarPor']) ? htmlspecialchars($_GET['ordenarPor']) : 'orgao_nome';
$ordem = isset($_GET['ordem']) ? strtolower(htmlspecialchars($_GET['ordem'])) : 'asc';

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
                <?php navBar(); ?>
                <?php cardDescription('<i class="fa-regular fa-building"></i> Órgãos e Instituições', '<p class="card-text mb-0">Por favor, preencha os dados do órgão ou instituição para cadastrá-lo no sistema.</p><p class="card-text mb-0">Os campos obrigatórios são:</p><p class="card-text mt-2 mb-0"><ul><li>Nome</li><li>Email</li><li>Município</li><li>Estado</li></ul></p>'); ?>
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

                                    $resultado = $orgaoController->novoOrgao($orgao);

                                    if ($resultado['status'] === 'success') {
                                        alert('success', $resultado['message'], 3);
                                    } elseif ($resultado['status'] === 'duplicated' || $resultado['status'] === 'invalid_email' || $resultado['status'] === 'bad_request') {
                                        alert('info', $resultado['message'], 3);
                                    } elseif ($resultado['status'] === 'error') {
                                        alert('danger', $resultado['message'], 3);
                                    }
                                }

                                ?>

                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-5 col-12">
                                        <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome " required>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <input type="email" class="form-control form-control-sm" name="email" placeholder="Email " required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="telefone" placeholder="Telefone (somente números)" maxlength="11">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <input type="text" class="form-control form-control-sm" name="endereco" placeholder="Endereço ">
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input type="text" class="form-control form-control-sm" name="cep" placeholder="CEP (somente números)" maxlength="8">
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="bairro" placeholder="Bairro">
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
                                                    if ($tipo['orgao_tipo_id'] === '1000') {
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
                                        <input type="text" class="form-control form-control-sm" name="site" placeholder="Site ou rede sociais">
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <textarea class="form-control form-control-sm" name="informacoes" rows="5" placeholder="Informações importantes desse órgão"></textarea>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body p-2">
                                <div class="table-responsive">
                                    <form class="row g-2 form_custom mb-2" method="GET" enctype="application/x-www-form-urlencoded">

                                        <div class="col-md-2 col-6">
                                            <select class="form-select form-select-sm" name="ordenarPor" required>
                                                <option value="orgao_nome" <?php echo $ordenarPor == 'orgao_nome' ? 'selected' : ''; ?>>Ordenar por | Nome</option>
                                                <option value="orgao_estado" <?php echo $ordenarPor == 'orgao_estado' ? 'selected' : ''; ?>>Ordenar por | Estado</option>
                                                <option value="orgao_tipo_nome" <?php echo $ordenarPor == 'orgao_tipo_nome' ? 'selected' : ''; ?>>Ordenar por | Tipo</option>
                                                <option value="orgao_criado_em" <?php echo $ordenarPor == 'orgao_criado_em' ? 'selected' : ''; ?>>Ordenar por | Criação</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <select class="form-select form-select-sm" name="ordem" required>
                                                <option value="asc" <?php echo $ordem == 'asc' ? 'selected' : ''; ?>>Ordem Crescente</option>
                                                <option value="desc" <?php echo $ordem == 'desc' ? 'selected' : ''; ?>>Ordem Decrescente</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <select class="form-select form-select-sm" name="itens" required>
                                                <option value="5" <?php echo $itens == 5 ? 'selected' : ''; ?>>5 itens</option>
                                                <option value="10" <?php echo $itens == 10 ? 'selected' : ''; ?>>10 itens</option>
                                                <option value="5" <?php echo $itens == 25 ? 'selected' : ''; ?>>25 itens</option>
                                                <option value="10" <?php echo $itens == 59 ? 'selected' : ''; ?>>50 itens</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 col-6">
                                            <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-magnifying-glass"></i></button>
                                        </div>

                                    </form>

                                    <table class="table table-striped table-bordered mb-2 custom_table">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Email</th>
                                                <th>Telefone</th>
                                                <th>Endereço</th>
                                                <th>Município/UF</th>
                                                <th>CEP</th>
                                                <th>Tipo</th>
                                                <th>Site/Redes Sociais</th>
                                                <th>Criado por | em</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $buscaOrgao = $orgaoController->listarOrgaos($pagina, $itens, $ordenarPor, $ordem);

                                            if ($buscaOrgao['status'] === 'success') {

                                                foreach ($buscaOrgao['dados'] as $orgao) {
                                                    $criado = date('d/m H:i', strtotime($orgao['orgao_criado_em']));

                                                    echo '
                                                            <tr>
                                                                <td><a href="' . $config['app']['url'] . '/orgaos/' . $orgao['orgao_id'] . '" id="link">' . $orgao['orgao_nome'] . '</a></td>
                                                                <td>' . $orgao['orgao_email'] . '</td>
                                                                <td>' . $orgao['orgao_telefone'] . '</td>
                                                                <td>' . $orgao['orgao_endereco'] . '  ' . $orgao['orgao_bairro'] . '</td>
                                                                <td>' . $orgao['orgao_municipio'] . '/' . $orgao['orgao_estado'] . '</td>
                                                                <td>' . $orgao['orgao_cep'] . '</td>
                                                                <td>' . $orgao['orgao_tipo_nome'] . '</td>
                                                                <td>' . $orgao['orgao_site'] . '</td>
                                                                <td>' . $orgao['usuario_nome'] . ' | ' . $criado . '</td>
                                                            </tr>
                                                        ';
                                                }
                                            } else if ($buscaOrgao['status'] === 'empty') {
                                                echo '<tr><td colspan="10">Nenhum órgão ou instituição registrado.</td></tr>';
                                            } else if ($buscaOrgao['status'] === 'error') {
                                                echo '<tr><td colspan="7">Erro interno do servidor.</td></tr>';
                                            }

                                            ?>
                                        </tbody>
                                    </table>
                                    <ul class="pagination custom-pagination mb-0">
                                        <?php
                                        if (isset($buscaOrgao['total_paginas'])) {
                                            $totalPagina = $buscaOrgao['total_paginas'];
                                        } else {
                                            $totalPagina = 0;
                                        }

                                        if ($totalPagina > 0 && $totalPagina != 1) {
                                            echo '<li class="page-item"><a class="page-link" href="./orgaos?itens=' . $itens . '&pagina=1&ordenarPor=' . $ordenarPor . '&ordem=' . $ordem . (isset($termo) ? '&termo=' . $termo : '') . '">Primeira</a></li>';

                                            for ($i = 1; $i < $totalPagina - 1; $i++) {
                                                echo '<li class="page-item"><a class="page-link" href="./orgaos?itens=' . $itens . '&pagina=' . ($i + 1) . '&ordenarPor=' . $ordenarPor . '&ordem=' . $ordem . (isset($termo) ? '&termo=' . $termo : '') . '">' . ($i + 1) . '</a></li>';
                                            }

                                            echo '<li class="page-item"><a class="page-link" href="./orgaos?itens=' . $itens . '&pagina=' . $totalPagina . '&ordenarPor=' . $ordenarPor . '&ordem=' . $ordem . (isset($termo) ? '&termo=' . $termo : '') . '">Última</a></li>';
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
                    selectEstado.append(`<option value="${estado.sigla}">${estado.sigla}</option>`);
                });
            });
        }

        function carregarMunicipios(estadoId) {
            $.getJSON(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${estadoId}/municipios?orderBy=nome`, function(data) {
                const selectMunicipio = $('#municipio');
                selectMunicipio.empty();
                selectMunicipio.append('<option value="" selected>Município</option>');
                data.forEach(municipio => {
                    selectMunicipio.append(`<option value="${municipio.nome}">${municipio.nome}</option>`);
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
                    window.location.href = "./orgaos-tipos";
                }
            }
        });
    </script>
</body>

</html>