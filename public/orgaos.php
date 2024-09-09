<?php
require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/OrgaoController.php';
$orgaoController = new OrgaoController();

require_once dirname(__DIR__) . '/app/controllers/OrgaoTipoController.php';
$orgaoTipoController = new OrgaoTipoController();


$itens = isset($_GET['itens']) ? (int)$_GET['itens'] : 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$ordenarPor = isset($_GET['ordenarPor']) ? htmlspecialchars($_GET['ordenarPor']) : 'orgao_nome';
$ordem = isset($_GET['ordem']) ? strtolower(htmlspecialchars($_GET['ordem'])) : 'asc';
$termo = isset($_GET['termo']) ? htmlspecialchars($_GET['termo']) : null;
$filtro = isset($_GET['filtro']) ? ($_GET['filtro'] == '1' ? true : false) : false;

$config = require dirname(__DIR__) . '/app/config/config.php';
$depConfig = $config['deputado'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Órgãos e instituições') ?>
</head>


<body class="bg-secondary">
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar() ?>
                <?php $layoutClass->cardDescription('<i class="fa-solid fa-user-plus"></i> Adicionar órgãos e instituições', '<p class="card-text mb-2">Seção para gerenciamento de órgãos e instituições</p><p class="card-text mb-0">Os campos <b>nome, email, município e estado</b> são obrigatórios</p>') ?>
                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2 ">
                            <div class="card-body p-0">
                                <nav class="navbar navbar-expand bg-body-tertiary p-0 ">
                                    <div class="container-fluid p-0">
                                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                            <ul class="navbar-nav me-auto mb-0 mb-lg-0">
                                                <li class="nav-item">
                                                    <a class="nav-link active" aria-current="page" href="<?php echo $config['app']['url'] ?>/orgaos-tipos">
                                                        <button class="btn btn-success btn-sm" style="font-size: 0.850em;" id="btn_novo_tipo" type="button">
                                                            <i class="fa-solid fa-circle-plus"></i> Novo tipo
                                                        </button>
                                                        <button class="btn btn-secondary btn-sm" style="font-size: 0.850em;" id="btn_imprimir" type="button">
                                                            <i class="fa-solid fa-print"></i> Imprimir
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

                                    $resultado = $orgaoController->NovoOrgao($orgao);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                    } else if ($resultado['status'] === 'duplicated') {
                                        $layoutClass->alert('info', $resultado['message'], 3);
                                    } else if ($resultado['status'] === 'error' || $resultado['status'] === 'bad_request') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }

                                ?>
                                <form class="row g-2 form_custom " id="form_novo" method="POST" enctype="application/x-www-form-urlencoded">
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
                                            $buscaTipos = $orgaoTipoController->ListarTipoOrgaos();
                                            if ($buscaTipos['status'] === 'success') {
                                                foreach ($buscaTipos['dados'] as $tipos) {
                                                    if ($tipos['orgao_tipo_id'] == 1000) {
                                                        echo '<option value="' . $tipos['orgao_tipo_id'] . '" selected>' . $tipos['orgao_tipo_nome'] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $tipos['orgao_tipo_id'] . '">' . $tipos['orgao_tipo_nome'] . '</option>';
                                                    }
                                                }
                                            } else if ($buscaTipos['status'] === 'empty') {
                                                echo ' <option value="1000" selected>Tipo não informado</option>';
                                            } else {
                                                echo ' <option value="1000" selected>Erro ao carregar tipos</option>';
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
                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <form class="row g-2 form_custom mb-0" method="GET" enctype="application/x-www-form-urlencoded">
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" name="ordenarPor" required>
                                            <option value="orgao_nome" <?php echo $ordenarPor == 'orgao_nome' ? 'selected' : ''; ?>>Ordenar por | Nome</option>
                                            <option value="orgao_estado" <?php echo $ordenarPor == 'orgao_estado' ? 'selected' : ''; ?>>Ordenar por | Estado</option>
                                            <option value="orgao_municipio" <?php echo $ordenarPor == 'orgao_municipio' ? 'selected' : ''; ?>>Ordenar por | Muncípio</option>
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
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" name="filtro" required>
                                            <option value="0" <?php echo $filtro == 0 ? 'selected' : ''; ?>>Todos os estados</option>
                                            <option value="1" <?php echo $filtro == 1 ? 'selected' : ''; ?>>Somente <?php echo $depConfig['estado_deputado'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="termo" placeholder="Buscar...">
                                    </div>
                                    <div class="col-md-1 col-6">
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $orgaos = $orgaoController->ListarOrgaos($itens, $pagina, $ordem, $ordenarPor, $termo, $filtro);
                $tabela = [];

                if ($orgaos['status'] == 'success' && $orgaos['status'] != 'empty') {
                    foreach ($orgaos['dados'] as $orgao) {
                        $tabela[] = [
                            'Nome' => '<a href="editar-orgao.php?id=' . $orgao['orgao_id'] . '">' . $orgao['orgao_nome'] . '</a>',
                            'Email' => $orgao['orgao_email'],
                            'Telefone' => $orgao['orgao_telefone'],
                            'Tipo' => $orgao['orgao_tipo_nome'],
                            'Muncípio/UF' => $orgao['orgao_municipio'] . '/' . $orgao['orgao_estado'],
                            'Criado em | por' => date('d/m', strtotime($orgao['orgao_criado_em'])) . ' | ' . $orgao['usuario_nome'],
                        ];
                    }
                    echo $layoutClass->criarTabela($tabela);
                } else if ($orgaos['status'] == 'error') {
                    echo $layoutClass->criarTabela([['Mensagem' => 'Erro interno do servidor.']]);
                } else {
                    echo $layoutClass->criarTabela([]);
                }

                ?>
                <ul class="pagination custom-pagination mb-0">
                    <?php
                    if (isset($orgaos['total_paginas'])) {
                        $totalPagina = $orgaos['total_paginas'];
                    } else {
                        $totalPagina = 0;
                    }

                    if ($totalPagina > 0 && $totalPagina != 1) {
                        echo '<li class="page-item"><a class="page-link" href="orgaos.php?itens=' . $itens . '&pagina=1&ordenarPor=' . $ordenarPor . '&ordem=' . $ordem . (isset($termo) ? '&termo=' . $termo : '') . '">Primeira</a></li>';

                        for ($i = 1; $i < $totalPagina - 1; $i++) {
                            echo '<li class="page-item"><a class="page-link" href="orgaos.php?itens=' . $itens . '&pagina=' . ($i + 1) . '&ordenarPor=' . $ordenarPor . '&ordem=' . $ordem . (isset($termo) ? '&termo=' . $termo : '') . '">' . ($i + 1) . '</a></li>';
                        }

                        echo '<li class="page-item"><a class="page-link" href="orgaos.php?itens=' . $itens . '&pagina=' . $totalPagina . '&ordenarPor=' . $ordenarPor . '&ordem=' . $ordem . (isset($termo) ? '&termo=' . $termo : '') . '">Última</a></li>';
                    }
                    ?>
                </ul>
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

        $('#btn_novo_tipo').click(function() {
            if (window.confirm("Você realmente deseja inserir um novo tipo?")) {
                window.location.href = "orgaos-tipos.php";
            }else{
                return false;
            }
        });


        $('#tipo').change(function() {
            if ($('#tipo').val() == '+') {
                if (window.confirm("Você realmente deseja inserir um novo tipo?")) {
                    window.location.href = "orgaos-tipos.php";
                }
            }
        });
    </script>
</body>

</html>