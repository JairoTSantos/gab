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

$id = $_GET['id'];

$buscarPessoa = $pessoaController->buscarPessoa('pessoa_id', $id);

if ($buscarPessoa['status'] === 'empty') {
    header("Location: ../pessoas");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php montarHeader('Editar pessoa'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php include 'includes/side_menu.php' ?>
        <div id="page-content-wrapper">
            <?php include 'includes/top_menu.php' ?>
            <div class="container-fluid p-2">
                <?php navBar(true, '../pessoas'); ?>
                <?php cardDescription('<i class="fas fa-user-friends"></i> Editar Pessoas', '<p class="card-text mb-0">Por favor, preencha os dados da pessoa para atualiza-la no sistema.</p><p class="card-text mb-0">Os campos obrigatórios são:</p><p class="card-text mt-2 mb-0"><ul><li>Nome</li><li>Aniversário</li><li>Email</li><li>Município</li><li>Estado</li></ul></p>'); ?>
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
                                                        <button class="btn btn-outline-success btn-sm" style="font-size: 0.850em;" id="btn_novo_tipo" type="button">
                                                            <i class="fa-solid fa-circle-plus"></i> Novo tipo
                                                        </button>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link active" aria-current="page" href="<?php echo $config['app']['url'] ?>/profissoes">
                                                        <button class="btn btn-outline-secondary btn-sm" style="font-size: 0.850em;" id="btn_nova_profissao" type="button">
                                                            <i class="fa-solid fa-circle-plus"></i> Nova profissão
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
                                        'pessoa_foto' => $_FILES['foto'],
                                        'pessoa_informacoes' => $_POST['informacoes']
                                    ];

                                    $resultado = $pessoaController->atualizarPessoa($id, $dados);

                                    if ($resultado['status'] === 'success') {
                                        alert('success', $resultado['message'], 3);
                                        $buscarPessoa = $pessoaController->buscarPessoa('pessoa_id', $id);
                                    } elseif ($resultado['status'] === 'duplicated' || $resultado['status'] === 'invalid_email' || $resultado['status'] === 'bad_request') {
                                        alert('info', $resultado['message'], 3);
                                    } elseif ($resultado['status'] === 'error') {
                                        alert('danger', $resultado['message'], 3);
                                    }
                                }

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                                    
                                    $resultado = $pessoaController->apagarPessoa($id);

                                    if ($resultado['status'] === 'success') {
                                        alert('success', $resultado['message'], 3);
                                        echo '<script>
                                                setTimeout(function(){
                                                    window.location.href = "../pessoas";
                                                }, 1000);
                                            </script>';
                                    } elseif ($resultado['status'] === 'error' || $resultado['status'] === 'invalid_id') {
                                        alert('danger', $resultado['message'], 3);
                                    }
                                }


                                ?>
                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
                                    <div class="col-md-4 col-12">
                                        <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome" value="<?php echo $buscarPessoa['dados']['pessoa_nome'] ?>" required>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <input type="text" class="form-control form-control-sm" name="email" placeholder="Email " value="<?php echo $buscarPessoa['dados']['pessoa_email'] ?>" required>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="date" class="form-control form-control-sm" name="aniversario" value="<?php echo $buscarPessoa['dados']['pessoa_aniversario'] ?>" required>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="text" class="form-control form-control-sm" name="telefone" placeholder="Telefone" value="<?php echo $buscarPessoa['dados']['pessoa_telefone'] ?>">
                                    </div>
                                    <div class="col-md-5 col-12">
                                        <input type="text" class="form-control form-control-sm" name="endereco" placeholder="Endereço" value="<?php echo $buscarPessoa['dados']['pessoa_endereco'] ?>">
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="text" class="form-control form-control-sm" name="bairro" placeholder="Bairro " value="<?php echo $buscarPessoa['dados']['pessoa_bairro'] ?>">
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="text" class="form-control form-control-sm" name="cep" placeholder="CEP" maxlength="8" value="<?php echo $buscarPessoa['dados']['pessoa_cep'] ?>">
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
                                            <option value="Sexo não informado" <?php echo $buscarPessoa['dados']['pessoa_sexo'] == 'Sexo não informado' ? 'selected' : ''; ?>>Sexo não informado</option>
                                            <option value="Masculino" <?php echo $buscarPessoa['dados']['pessoa_sexo'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                                            <option value="Feminino" <?php echo $buscarPessoa['dados']['pessoa_sexo'] == 'Feminino' ? 'selected' : ''; ?>>Feminino</option>
                                            <option value="Outro" <?php echo $buscarPessoa['dados']['pessoa_sexo'] == 'Outro' ? 'selected' : ''; ?>>Outro</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <input type="text" class="form-control form-control-sm" name="facebook" placeholder="@facebook " value="<?php echo $buscarPessoa['dados']['pessoa_facebook'] ?>">
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <input type="text" class="form-control form-control-sm" name="instagram" placeholder="@instagram " value="<?php echo $buscarPessoa['dados']['pessoa_instagram'] ?>">
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <input type="text" class="form-control form-control-sm" name="x" placeholder="@X (Twitter) " value="<?php echo $buscarPessoa['dados']['pessoa_x'] ?>">
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <select class="form-select form-select-sm" id="orgao" name="orgao">
                                            <option value="1000" selected>Órgão não informado</option>
                                            <?php

                                            $buscaOrgao = $orgaoController->listarOrgaos(1, 1000);

                                            if ($buscaOrgao['status'] === 'success') {
                                                foreach ($buscaOrgao['dados'] as $orgao) {
                                                    if ($orgao['orgao_id'] === $buscarPessoa['dados']['pessoa_orgao']) {
                                                        echo '<option value="' . $orgao['orgao_id'] . '" selected>' . $orgao['orgao_nome'] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $orgao['orgao_id'] . '">' . $orgao['orgao_nome'] . '</option>';
                                                    }
                                                }
                                            }

                                            ?>

                                            <option value="+">Novo órgão + </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <select class="form-select form-select-sm" id="tipo" name="tipo" required>
                                            <option value="1000">Sem tipo definido</option>
                                            <?php
                                            $buscaTipo = $pessoaController->listarTiposPessoas();
                                            print_r($buscaTipo);
                                            if ($buscaTipo['status'] === 'success') {
                                                foreach ($buscaTipo['dados'] as $tipo) {
                                                    if ($tipo['pessoa_tipo_id'] === $buscarPessoa['dados']['pessoa_tipo']) {
                                                        echo '<option value="' . $tipo['pessoa_tipo_id'] . '" selected>' . $tipo['pessoa_tipo_nome'] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $tipo['pessoa_tipo_id'] . '">' . $tipo['pessoa_tipo_nome'] . '</option>';
                                                    }
                                                }
                                            }
                                            ?>

                                            <option value="+">Novo tipo + </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <select class="form-select form-select-sm" id="profissao" name="profissao" required>
                                            <option value="1000">Profissão não informada</option>
                                            <?php
                                            $buscaProfissao = $pessoaController->listarProfissoesPessoas();
                                            if ($buscaProfissao['status'] === 'success') {
                                                foreach ($buscaProfissao['dados'] as $profissao) {
                                                    if ($profissao['pessoas_profissoes_id'] === $buscarPessoa['dados']['pessoa_profissao']) {
                                                        echo '<option value="' . $profissao['pessoas_profissoes_id'] . '" selected>' . $profissao['pessoas_profissoes_nome'] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $profissao['pessoas_profissoes_id'] . '">' . $profissao['pessoas_profissoes_nome'] . '</option>';
                                                    }
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
                                        <input type="file" class="form-control form-control-sm" name="foto" placeholder="Foto">
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <textarea class="form-control form-control-sm" name="informacoes" rows="5" placeholder="Informações importantes dessa pessoa"><?php echo $buscarPessoa['dados']['pessoa_informacoes'] ?></textarea>
                                    </div>

                                    <div class="col-md-2 col-6">
                                        <button type="submit" class="btn btn-success btn-sm" name="btn_salvar" id="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
                                        <button type="submit" class="btn btn-danger btn-sm" name="btn_apagar" id="btn_apagar"><i class="fa-solid fa-trash"></i> Apagar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 col-12">
                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-2">
                                <?php
                                if ($buscarPessoa['dados']['pessoa_foto'] != null) {
                                    echo ' <img class="img-fluid" src="../' . $buscarPessoa['dados']['pessoa_foto'] . '" alt="" />';
                                } else {
                                    echo ' <img class="img-fluid" src="../public/images/not_found.jpg" alt="" />';
                                }
                                ?>

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
                const confirmacao = confirm("Tem certeza que deseja atualizar essa pessoa?");
                if (!confirmacao) {
                    event.preventDefault();
                }
            });

            $("#btn_apagar").click(function(event) {
                const confirmacao = confirm("Tem certeza que deseja apagar essa pessoa?");
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
                    if (estado.sigla === "<?php echo $buscarPessoa['dados']['pessoa_estado'] ?>") {
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
                    if (municipio.nome === "<?php echo $buscarPessoa['dados']['pessoa_municipio'] ?>") {
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
                    window.location.href = "<?php echo $config['app']['url'] ?>/pessoas-tipos";
                }
            }
        });

        $('#profissao').change(function() {
            if ($('#profissao').val() == '+') {
                if (window.confirm("Você realmente deseja inserir uma nova profissão?")) {
                    window.location.href = "<?php echo $config['app']['url'] ?>/profissoes";
                }
            }
        });

        $('#orgao').change(function() {
            if ($('#orgao').val() == '+') {
                if (window.confirm("Você realmente deseja inserir um novo órgão?")) {
                    window.location.href = "<?php echo $config['app']['url'] ?>/orgaos";
                }
            }
        });

        $('#btn_novo_tipo').click(function() {
            if (window.confirm("Você realmente deseja inserir um novo tipo?")) {
                window.location.href = "./pessoas-tipos";
            } else {
                return false;
            }
        });



        $('#btn_nova_profissao').click(function() {
            if (window.confirm("Você realmente deseja inserir uma nova profissão?")) {
                window.location.href = "./profissoes";
            } else {
                return false;
            }
        });
    </script>

</body>

</html>