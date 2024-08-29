<?php

require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/verificaLogado.php';

require_once dirname(__DIR__) . '/app/controllers/PessoaController.php';
$pessoaController = new PessoaController();

require_once dirname(__DIR__) . '/app/controllers/OrgaoController.php';
$orgaoController = new OrgaoController();

$itens = isset($_GET['itens']) ? (int)$_GET['itens'] : 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$ordenarPor = isset($_GET['ordenarPor']) ? htmlspecialchars($_GET['ordenarPor']) : 'pessoa_nome';
$ordem = isset($_GET['ordem']) ? strtolower(htmlspecialchars($_GET['ordem'])) : 'asc';



?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php montarHeader('Pessoas'); ?>
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
                <?php cardDescription('<i class="fas fa-user-friends"></i> Pessoas', '<p class="card-text mb-0">Por favor, preencha os dados da pessoa para cadastrá-lo no sistema.</p><p class="card-text mb-0">Os campos obrigatórios são:</p><p class="card-text mt-2 mb-0"><ul><li>Nome</li><li>Aniversário</li><li>Email</li><li>Município</li><li>Estado</li></ul></p>'); ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-0">
                                <nav class="navbar navbar-expand bg-body-tertiary p-0">
                                    <div class="container-fluid p-0">
                                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                            <ul class="navbar-nav me-auto mb-0 mb-lg-0">
                                                <li class="nav-item">
                                                    <a class="nav-link active" aria-current="page" href="<?php echo $config['app']['url'] ?>/pessoas-tipos">
                                                        <button class="btn btn-outline-success btn-sm" style="font-size: 0.850em;" type="button">
                                                            <i class="fa-solid fa-circle-plus"></i> Novo tipo
                                                        </button>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link active" aria-current="page" href="<?php echo $config['app']['url'] ?>/profissoes">
                                                        <button class="btn btn-outline-secondary btn-sm" style="font-size: 0.850em;" type="button">
                                                            <i class="fa-solid fa-circle-plus"></i> Nova profissão
                                                        </button>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link active" aria-current="page" href="<?php echo $config['app']['url'] ?>/ficha">
                                                        <button class="btn btn-outline-danger btn-sm" style="font-size: 0.850em;" type="button">
                                                            <i class="fa-solid fa-print"></i> Imprimir ficha para cadastro
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
                                        'pessoa_informacoes' => $_POST['informacoes']
                                    ];

                                    $resultado = $pessoaController->novaPessoa($dados);

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
                                    <div class="col-md-4 col-12">
                                        <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome " required>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <input type="text" class="form-control form-control-sm" name="email" placeholder="Email " required>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input type="date" class="form-control form-control-sm" name="aniversario" placeholder="Nome " required>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input type="text" class="form-control form-control-sm" name="telefone" placeholder="Telefone (Somente números)">
                                    </div>
                                    <div class="col-md-5 col-12">
                                        <input type="text" class="form-control form-control-sm" name="endereco" placeholder="Endereço ">
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input type="text" class="form-control form-control-sm" name="cep" placeholder="CEP (Somente números)" maxlength="8">
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input type="text" class="form-control form-control-sm" name="bairro" placeholder="Bairro ">
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
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" id="sexo" name="sexo" required>
                                            <option value="Sexo não informado" selected>Sexo não informado</option>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Feminino">Feminino</option>
                                            <option value="Outro">Outro</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input type="text" class="form-control form-control-sm" name="facebook" placeholder="@facebook ">
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input type="text" class="form-control form-control-sm" name="instagram" placeholder="@instagram ">
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input type="text" class="form-control form-control-sm" name="x" placeholder="@X (Twitter) ">
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" id="orgao" name="orgao">
                                            <option value="1000" selected>Órgão não informado</option>
                                            <?php

                                            $buscaOrgao = $orgaoController->listarOrgaos(1, 1000);
                                            if ($buscaOrgao['status'] === 'success') {
                                                foreach ($buscaOrgao['dados'] as $orgao) {
                                                    echo '<option value="' . $orgao['orgao_id'] . '">' . $orgao['orgao_nome'] . '</option>';
                                                }
                                            }

                                            ?>

                                            <option value="+">Novo órgão + </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <select class="form-select form-select-sm" id="tipo" name="tipo" required>
                                            <option value="1000" selected>Sem tipo definido</option>
                                            <?php
                                            $buscaTipo = $pessoaController->listarTiposPessoas();
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
                                            $buscaProfissao = $pessoaController->listarProfissoesPessoas();
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
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body p-2">
                                <div class="table-responsive">
                                    <form class="row g-2 form_custom mb-2" method="GET" enctype="application/x-www-form-urlencoded">
                                        <div class="col-md-2 col-6">
                                            <select class="form-select form-select-sm" name="ordenarPor" required>
                                                <option value="pessoa_nome" <?php echo $ordenarPor == 'pessoa_nome' ? 'selected' : ''; ?>>Ordenar por | Nome</option>
                                                <option value="pessoa_estado" <?php echo $ordenarPor == 'pessoa_estado' ? 'selected' : ''; ?>>Ordenar por | Estado</option>
                                                <option value="pessoa_tipo_nome" <?php echo $ordenarPor == 'pessoa_tipo_nome' ? 'selected' : ''; ?>>Ordenar por | Tipo</option>
                                                <option value="pessoa_criada_em" <?php echo $ordenarPor == 'pessoa_criada_em' ? 'selected' : ''; ?>>Ordenar por | Criação</option>
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
                                                <th>Endereco</th>
                                                <th>Município/UF</th>
                                                <th>Tipo</th>
                                                <th>Profissão</th>
                                                <th>Órgão</th>
                                                <th>Criado por | em</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $buscaPessoa = $pessoaController->listarPessoas($pagina, $itens, $ordenarPor, $ordem);

                                            if ($buscaPessoa['status'] === 'success') {
                                                foreach ($buscaPessoa['dados'] as $pessoa) {
                                                    $criado = date('d/m H:i', strtotime($pessoa['pessoa_criada_em']));
                                                    echo '
                                                        <tr>
                                                            <td><a href="' . $config['app']['url'] . '/pessoas/' . $pessoa['pessoa_id'] . '" id="link">' . $pessoa['pessoa_nome'] . '</a></td>
                                                            <td>' . $pessoa['pessoa_email'] . '</td>
                                                            <td>' . $pessoa['pessoa_telefone'] . '</td>
                                                            <td>' . $pessoa['pessoa_endereco'] . '  ' . $pessoa['pessoa_bairro'] . '</td>
                                                            <td>' . $pessoa['pessoa_municipio'] . '/' . $pessoa['pessoa_estado'] . '</td>
                                                            <td>' . $pessoa['pessoa_tipo_nome'] . '</td>
                                                            <td>' . $pessoa['pessoas_profissoes_nome'] . '</td>
                                                            <td>' . $pessoa['orgao_nome'] . '</td>
                                                            <td>' . $pessoa['usuario_nome'] . ' | ' . $criado . '</td>
                                                        </tr>
                                                    ';
                                                }
                                            } else if ($buscaPessoa['status'] === 'empty') {
                                                echo '<tr><td colspan="10">Nenhuma pessoa registrada.</td></tr>';
                                            } else if ($buscaPessoa['status'] === 'error') {
                                                echo '<tr><td colspan="7">Erro interno do servidor.</td></tr>';
                                            }

                                            ?>
                                        </tbody>
                                    </table>
                                    <ul class="pagination custom-pagination mb-0">
                                        <?php
                                        if (isset($buscaPessoa['total_paginas'])) {
                                            $totalPagina = $buscaPessoa['total_paginas'];
                                        } else {
                                            $totalPagina = 0;
                                        }

                                        if ($totalPagina > 0 && $totalPagina != 1) {
                                            echo '<li class="page-item"><a class="page-link" href="./pessoas?itens=' . $itens . '&pagina=1&ordenarPor=' . $ordenarPor . '&ordem=' . $ordem . (isset($termo) ? '&termo=' . $termo : '') . '">Primeira</a></li>';

                                            for ($i = 1; $i < $totalPagina - 1; $i++) {
                                                echo '<li class="page-item"><a class="page-link" href="./pessoas?itens=' . $itens . '&pagina=' . ($i + 1) . '&ordenarPor=' . $ordenarPor . '&ordem=' . $ordem . (isset($termo) ? '&termo=' . $termo : '') . '">' . ($i + 1) . '</a></li>';
                                            }

                                            echo '<li class="page-item"><a class="page-link" href="./pessoas?itens=' . $itens . '&pagina=' . $totalPagina . '&ordenarPor=' . $ordenarPor . '&ordem=' . $ordem . (isset($termo) ? '&termo=' . $termo : '') . '">Última</a></li>';
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
                    window.location.href = "./pessoas-tipos";
                }
            }
        });

        $('#profissao').change(function() {
            if ($('#profissao').val() == '+') {
                if (window.confirm("Você realmente deseja inserir uma nova profissão?")) {
                    window.location.href = "./profissoes";
                }
            }
        });

        $('#orgao').change(function() {
            if ($('#orgao').val() == '+') {
                if (window.confirm("Você realmente deseja inserir um novo órgão?")) {
                    window.location.href = "./orgaos";
                }
            }
        });
    </script>

</body>

</html>