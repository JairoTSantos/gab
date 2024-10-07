<?php
require_once dirname(__DIR__) . '/public/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/controllers/PessoaController.php';
$pessoaController = new PessoaController();

require_once dirname(__DIR__) . '/app/controllers/TipoPessoaController.php';
$tipoPessoaController = new PessoaTipoController();

require_once dirname(__DIR__) . '/app/controllers/ProfissaoController.php';
$profissaoController = new ProfissaoController();

require_once dirname(__DIR__) . '/app/controllers/OrgaoController.php';
$orgaoController = new OrgaoController();

$itens = isset($_GET['itens']) ? (int)$_GET['itens'] : 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$ordenarPor = isset($_GET['ordenarPor']) ? htmlspecialchars($_GET['ordenarPor']) : 'pessoa_nome';
$ordem = isset($_GET['ordem']) ? strtolower(htmlspecialchars($_GET['ordem'])) : 'asc';
$termo = isset($_GET['termo']) ? htmlspecialchars($_GET['termo']) : null;
$filtro = isset($_GET['filtro']) ? ($_GET['filtro'] == '1' ? true : false) : false;

$config = require dirname(__DIR__) . '/app/config/config.php';
$depConfig = $config['deputado'];

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Pessoas'); ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php $layoutClass->MontarSideMenu() ?>
        <div id="page-content-wrapper">
            <?php $layoutClass->MontarTopMenu() ?>
            <div class="container-fluid p-2">
                <?php $layoutClass->navBar() ?>
                <?php $layoutClass->cardDescription('<i class="fa-solid fa-person"></i> Adicionar pessoas', '<p class="card-text mb-2">Seção para gerenciamento de pessoas de interesse do mandato</p><p class="card-text mb-0">Os campos <b>nome, email, aniversário, município e estado</b> são obrigatórios</p>') ?>
                <div class="row ">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2 ">
                            <div class="card-body p-0">
                                <nav class="navbar navbar-expand bg-body-tertiary p-0 ">
                                    <div class="container-fluid p-0">
                                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                            <ul class="navbar-nav me-auto mb-0 mb-lg-0">
                                                <li class="nav-item">
                                                    <a class="nav-link active p-1" aria-current="page" href="#">
                                                        <button class="btn btn-success btn-sm" style="font-size: 0.850em;" id="btn_novo_tipo" type="button">
                                                            <i class="fa-solid fa-circle-plus"></i> Novo tipo
                                                        </button>
                                                        <button class="btn btn-primary btn-sm" style="font-size: 0.850em;" id="btn_nova_profissao" type="button">
                                                            <i class="fa-solid fa-circle-plus"></i> Nova profissão
                                                        </button>
                                                        <button class="btn btn-secondary btn-sm" style="font-size: 0.850em;" id="btn_novo_orgao" type="button">
                                                            <i class="fa-solid fa-circle-plus"></i> Novo órgão
                                                        </button>
                                                        <!--<button class="btn btn-secondary btn-sm" style="font-size: 0.850em;" id="btn_imprimir" type="button">
                                                            <i class="fa-solid fa-print"></i> Imprimir
                                                        </button>-->
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

                                    $dados = [
                                        'pessoa_nome' => $_POST['nome'],
                                        'pessoa_email' => $_POST['email'],
                                        'pessoa_aniversario' => $_POST['aniversario'],
                                        'pessoa_telefone' => $_POST['telefone'],
                                        'pessoa_endereco' => $_POST['endereco'],
                                        'pessoa_cep' => $_POST['cep'],
                                        'pessoa_bairro' => $_POST['bairro'],
                                        'pessoa_estado' => $_POST['estado'],
                                        'pessoa_municipio' => $_POST['municipio'],
                                        'pessoa_sexo' => $_POST['sexo'],
                                        'pessoa_facebook' => $_POST['facebook'],
                                        'pessoa_instagram' => $_POST['instagram'],
                                        'pessoa_x' => $_POST['x'],
                                        'pessoa_tipo' => $_POST['tipo'],
                                        'pessoa_profissao' => $_POST['profissao'],
                                        'pessoa_cargo' => $_POST['cargo'],
                                        'pessoa_orgao' => $_POST['orgao'],
                                        'pessoa_informacoes' => $_POST['informacoes'],
                                        'foto' => $_FILES['foto']
                                    ];

                                    $resultado = $pessoaController->novaPessoa($dados);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                    } else if ($resultado['status'] === 'file_not_permitted' || $resultado['status'] === 'duplicated' || $resultado['status'] === 'file_too_large') {
                                        $layoutClass->alert('info', $resultado['message'], 3);
                                    } else if ($resultado['status'] === 'error') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }

                                ?>


                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
                                    <div class="col-md-4 col-12">
                                        <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome " required>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <input type="text" class="form-control form-control-sm" name="email" placeholder="Email " required>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="date" class="form-control form-control-sm" name="aniversario" placeholder="Nome " required>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="text" class="form-control form-control-sm" name="telefone" placeholder="Telefone (Somente números)">
                                    </div>
                                    <div class="col-md-5 col-12">
                                        <input type="text" class="form-control form-control-sm" name="endereco" placeholder="Endereço ">
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="text" class="form-control form-control-sm" name="bairro" placeholder="Bairro ">
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="text" class="form-control form-control-sm" name="cep" placeholder="CEP (Somente números)" maxlength="8">
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
                                    <div class="col-md-2 col-12">
                                        <select class="form-select form-select-sm" id="sexo" name="sexo" required>
                                            <option value="Sexo não informado" selected>Sexo não informado</option>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Feminino">Feminino</option>
                                            <option value="Outro">Outro</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <input type="text" class="form-control form-control-sm" name="facebook" placeholder="@facebook ">
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <input type="text" class="form-control form-control-sm" name="instagram" placeholder="@instagram ">
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <input type="text" class="form-control form-control-sm" name="x" placeholder="@X (Twitter) ">
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <select class="form-select form-select-sm" id="orgao" name="orgao">
                                            <option value="1000" selected>Órgão não informado</option>
                                            <?php

                                            $buscaOrgao = $orgaoController->listarOrgaos(1000, 1);

                                            print_r($buscaOrgao);
                                            if ($buscaOrgao['status'] === 'success') {
                                                foreach ($buscaOrgao['dados'] as $orgao) {
                                                    echo '<option value="' . $orgao['orgao_id'] . '">' . $orgao['orgao_nome'] . '</option>';
                                                }
                                            }
                                            ?>

                                            <option value="+">Novo órgão + </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <select class="form-select form-select-sm" id="tipo" name="tipo" required>
                                            <option value="1000" selected>Sem tipo definido</option>
                                            <?php
                                            $buscaTipo = $tipoPessoaController->listarTiposPessoas();
                                            if ($buscaTipo['status'] === 'success') {
                                                foreach ($buscaTipo['dados'] as $tipo) {
                                                    echo '<option value="' . $tipo['pessoa_tipo_id'] . '">' . $tipo['pessoa_tipo_nome'] . '</option>';
                                                }
                                            }
                                            ?>
                                            <option value="+">Novo tipo + </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <select class="form-select form-select-sm" id="profissao" name="profissao" required>
                                            <option value="1000" selected>Profissão não informada</option>
                                            <?php
                                            $buscaProfissao = $profissaoController->ListarProfissoes();
                                            if ($buscaProfissao['status'] === 'success') {
                                                foreach ($buscaProfissao['dados'] as $profissao) {
                                                    echo '<option value="' . $profissao['pessoas_profissoes_id'] . '">' . $profissao['pessoas_profissoes_nome'] . '</option>';
                                                }
                                            }
                                            ?>
                                            <option value="+">Nova profissao + </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="cargo" placeholder="Cargo (Diretor, assessor, coordenador....)">
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="file-upload">
                                            <input type="file" id="file-input" name="foto" style="display: none;" />
                                            <button id="file-button" type="button" class="btn btn-primary btn-sm"><i class="fa-regular fa-image"></i> Escolher Foto</button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <textarea class="form-control form-control-sm" name="informacoes" rows="5" placeholder="Informações importantes dessa pessoa"></textarea>
                                    </div>

                                    <div class="col-md-2 col-6">

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
                                            <option value="pessoa_nome" <?php echo $ordenarPor == 'pessoa_nome' ? 'selected' : ''; ?>>Ordenar por | Nome</option>
                                            <option value="pessoa_estado" <?php echo $ordenarPor == 'pessoa_estado' ? 'selected' : ''; ?>>Ordenar por | Estado</option>
                                            <option value="pessoa_municipio" <?php echo $ordenarPor == 'pessoa_municipio' ? 'selected' : ''; ?>>Ordenar por | Muncípio</option>
                                            <option value="pessoa_tipo_nome" <?php echo $ordenarPor == 'pessoa_tipo_nome' ? 'selected' : ''; ?>>Ordenar por | Tipo</option>
                                            <option value="pessoa_criado_em" <?php echo $ordenarPor == 'pessoa_criado_em' ? 'selected' : ''; ?>>Ordenar por | Criação</option>
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
                                            <option value="25" <?php echo $itens == 25 ? 'selected' : ''; ?>>25 itens</option>
                                            <option value="50" <?php echo $itens == 50 ? 'selected' : ''; ?>>50 itens</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" name="filtro" required>
                                            <option value="0" <?php echo $filtro == 0 ? 'selected' : ''; ?>>Todos os estados</option>
                                            <option value="1" <?php echo $filtro == 1 ? 'selected' : ''; ?>>Somente <?php echo $depConfig['estado_deputado'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-sm" name="termo" placeholder="Buscar por nome..." value="<?php echo $termo ?>">
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
                $pessoas = $pessoaController->ListarPessoas($itens, $pagina, $ordem, $ordenarPor, $termo, $filtro);
                $tabela = [];

                if ($pessoas['status'] == 'success' && $pessoas['status'] != 'empty') {
                    foreach ($pessoas['dados'] as $pessoa) {
                        $tabela[] = [
                            'Nome' => '<a href="editar-pessoa.php?id=' . $pessoa['pessoa_id'] . '">' . $pessoa['pessoa_nome'] . '</a>',
                            'Email' =>  $pessoa['pessoa_email'],
                            'Telefone' =>  $pessoa['pessoa_telefone'],
                            'Município/UF' =>  $pessoa['pessoa_municipio'] . ' - ' . $pessoa['pessoa_estado'],
                            'Tipo' =>  '<b>' . $pessoa['pessoa_tipo_nome'] . '</b>',
                            'Órgão' =>  $pessoa['orgao_nome'],
                            'Criado em | por' => date('d/m', strtotime($pessoa['pessoa_criada_em'])) . ' | ' . $pessoa['usuario_nome'],
                        ];
                    }
                    echo $layoutClass->criarTabela($tabela);
                } else if ($pessoas['status'] == 'error') {
                    echo $layoutClass->criarTabela([['Mensagem' => 'Erro interno do servidor.']]);
                } else {
                    echo $layoutClass->criarTabela([]);
                }

                ?>
                <ul class="pagination custom-pagination mb-0">
                    <?php
                    if (isset($pessoas['total_paginas'])) {
                        $totalPagina = $pessoas['total_paginas'];
                    } else {
                        $totalPagina = 0;
                    }

                    $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                    $maxLinks = 6; // Máximo de links a serem exibidos

                    if ($totalPagina > 0 && $totalPagina != 1) {
                        echo '<li class="page-item"><a class="page-link" href="pessoas.php?itens=' . $itens . '&filtro=' . $filtro . '&pagina=1&ordenarPor=' . $ordenarPor . '&ordem=' . $ordem . (isset($termo) ? '&termo=' . $termo : '') . '">Primeira</a></li>';

                        $inicio = max(1, $paginaAtual - floor($maxLinks / 2));
                        $fim = min($totalPagina, $inicio + $maxLinks - 1);

                        if ($fim - $inicio < $maxLinks - 1) {
                            $inicio = max(1, $fim - $maxLinks + 1);
                        }

                        for ($i = $inicio; $i <= $fim; $i++) {
                            echo '<li class="page-item' . ($i == $paginaAtual ? ' active' : '') . '"><a class="page-link" href="pessoas.php?itens=' . $itens . '&filtro=' . $filtro . '&pagina=' . $i . '&ordenarPor=' . $ordenarPor . '&ordem=' . $ordem . (isset($termo) ? '&termo=' . $termo : '') . '">' . $i . '</a></li>';
                        }

                        echo '<li class="page-item"><a class="page-link" href="pessoas.php?itens=' . $itens . '&filtro=' . $filtro . '&pagina=' . $totalPagina . '&ordenarPor=' . $ordenarPor . '&ordem=' . $ordem . (isset($termo) ? '&termo=' . $termo : '') . '">Última</a></li>';
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
                window.location.href = "pessoas-tipos.php";
            } else {
                return false;
            }
        });

        $('#btn_nova_profissao').click(function() {
            if (window.confirm("Você realmente deseja inserir uma nova profissão?")) {
                window.location.href = "profissoes.php";
            } else {
                return false;
            }
        });


        $('#tipo').change(function() {
            if ($('#tipo').val() == '+') {
                if (window.confirm("Você realmente deseja inserir um novo tipo?")) {
                    window.location.href = "pessoas-tipos.php";
                }
            }
        });


        $('#profissao').change(function() {
            if ($('#profissao').val() == '+') {
                if (window.confirm("Você realmente deseja inserir uma nova profissão?")) {
                    window.location.href = "profissoes.php";
                }
            }
        });

        $('#orgao').change(function() {
            if ($('#orgao').val() == '+') {
                if (window.confirm("Você realmente deseja inserir um novo órgão?")) {
                    window.location.href = "orgaos.php";
                }
            }
        });

        $('#btn_novo_orgao').click(function() {

            if (window.confirm("Você realmente deseja inserir um novo órgão?")) {
                window.location.href = "orgaos.php";
            }

        });

        $('#file-button').on('click', function() {
            $('#file-input').click();
        });

        $('#file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $('#file-button').html(fileName ? '<i class="fa-regular fa-circle-check"></i> Foto selecionada' : 'Nenhuma foto selecionada');
        });
    </script>
</body>

</html>