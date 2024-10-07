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


$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$buscaPessoa = $pessoaController->BuscarPessoa('pessoa_id', $id);

if ($buscaPessoa['status'] == 'empty' || $buscaPessoa['status'] == 'error') {
    header('Location: pessoas.php');
}

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

                <?php $layoutClass->navBar(true, 'pessoas.php') ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm mb-2 card-background">
                            <div class="card-body p-2">
                                <div class="row">
                                    <div class="col-12 col-md-1">
                                        <?php
                                        if (isset($buscaPessoa['dados']['pessoa_foto'])) {
                                            if ($buscaPessoa['dados']['pessoa_tipo'] == 1008 || $buscaPessoa['dados']['pessoa_tipo'] == 1009) {
                                                echo '<img src="' . $buscaPessoa['dados']['pessoa_foto'] . '" class="img-thumbnail img-crop" alt="...">';
                                            } else {
                                                echo '<img src="..' . $buscaPessoa['dados']['pessoa_foto'] . '" class="img-thumbnail img-crop" alt="...">';
                                            }
                                        } else {
                                            echo '<img src="img/not_found.jpg" class="img-thumbnail img-crop" alt="...">';
                                        }
                                        ?>
                                    </div>
                                    <div class="col-12 col-md-11 mt-2 ">
                                        <h5 class="card-title"><?php echo $buscaPessoa['dados']['pessoa_nome']; ?></h5>
                                        <p class="card-text mb-2"><i class="fa-solid fa-envelope"></i> <?php echo $buscaPessoa['dados']['pessoa_email']; ?></p>
                                        <p class="card-text mb-2"><i class="fa-solid fa-mobile-screen"></i> <?php echo $buscaPessoa['dados']['pessoa_telefone'] ? $buscaPessoa['dados']['pessoa_telefone'] : 'Sem telefone'; ?></p>
                                        <p class="card-text mb-2"><i class="fa-solid fa-cake-candles"></i> <?php echo date('d/M', strtotime($buscaPessoa['dados']['pessoa_aniversario'])); ?></p>
                                    </div>
                                </div>
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

                                    $resultado = $pessoaController->AtualizarPessoa($id, $dados);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                        echo '<script>
                                        setTimeout(function(){
                                            window.location.href = "editar-pessoa.php?id=' . $id . '";
                                        }, 1000);
                                    </script>';
                                    } else if ($resultado['status'] === 'error' || $resultado['status'] === 'bad_request') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }

                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                                    $resultado = $pessoaController->ApagarPessoa($id);

                                    if ($resultado['status'] === 'success') {
                                        $layoutClass->alert('success', $resultado['message'], 3);
                                        echo '<script>
                                                    setTimeout(function(){
                                                        window.location.href = "pessoas.php";
                                                    }, 500);
                                                </script>';
                                    } elseif ($resultado['status'] === 'error' || $resultado['status'] === 'invalid_id' || $resultado['status'] === 'delete_conflict') {
                                        $layoutClass->alert('danger', $resultado['message'], 3);
                                    }
                                }

                                ?>


                                <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
                                    <div class="col-md-4 col-12">
                                        <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome" value="<?php echo $buscaPessoa['dados']['pessoa_nome']; ?>" required>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <input type="text" class="form-control form-control-sm" name="email" placeholder="Email " value="<?php echo $buscaPessoa['dados']['pessoa_email']; ?>" required>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="date" class="form-control form-control-sm" name="aniversario" value="<?php echo $buscaPessoa['dados']['pessoa_aniversario']; ?>" required>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="text" class="form-control form-control-sm" name="telefone" placeholder="Telefone (Somente números)" value="<?php echo $buscaPessoa['dados']['pessoa_telefone']; ?>">
                                    </div>
                                    <div class="col-md-5 col-12">
                                        <input type="text" class="form-control form-control-sm" name="endereco" placeholder="Endereço " value="<?php echo $buscaPessoa['dados']['pessoa_endereco']; ?>">
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="text" class="form-control form-control-sm" name="bairro" placeholder="Bairro " value="<?php echo $buscaPessoa['dados']['pessoa_bairro']; ?>">
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <input type="text" class="form-control form-control-sm" name="cep" placeholder="CEP (Somente números)" maxlength="8" value="<?php echo $buscaPessoa['dados']['pessoa_cep']; ?>">
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
                                            <option value="Sexo não informado" <?php echo $buscaPessoa['dados']['pessoa_sexo'] == 'Sexo não informado' ? 'selected' : ''; ?>>Sexo não informado</option>
                                            <option value="Masculino" <?php echo $buscaPessoa['dados']['pessoa_sexo'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                                            <option value="Feminino" <?php echo $buscaPessoa['dados']['pessoa_sexo'] == 'Feminino' ? 'selected' : ''; ?>>Feminino</option>
                                            <option value="Outro" <?php echo $buscaPessoa['dados']['pessoa_sexo'] == 'Outro' ? 'selected' : ''; ?>>Outro</option>
                                        </select>

                                    </div>
                                    <div class="col-md-2 col-4">
                                        <input type="text" class="form-control form-control-sm" name="facebook" placeholder="@facebook " value="<?php echo $buscaPessoa['dados']['pessoa_facebook']; ?>">
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <input type="text" class="form-control form-control-sm" name="instagram" placeholder="@instagram " value="<?php echo $buscaPessoa['dados']['pessoa_instagram']; ?>">
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <input type="text" class="form-control form-control-sm" name="x" placeholder="@X (Twitter) " value="<?php echo $buscaPessoa['dados']['pessoa_x']; ?>">
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <select class="form-select form-select-sm" id="orgao" name="orgao">
                                            <option value="1000" selected>Órgão não informado</option>
                                            <?php

                                            $buscaOrgao = $orgaoController->listarOrgaos(1000, 1);
                                            if ($buscaOrgao['status'] === 'success') {
                                                foreach ($buscaOrgao['dados'] as $orgao) {
                                                    if ($orgao['orgao_id'] === $buscaPessoa['dados']['pessoa_orgao']) {
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
                                            <option value="1000" selected>Sem tipo definido</option>
                                            <?php
                                            $buscaTipo = $tipoPessoaController->listarTiposPessoas();
                                            if ($buscaTipo['status'] === 'success') {
                                                foreach ($buscaTipo['dados'] as $tipo) {
                                                    if ($tipo['pessoa_tipo_id'] == $buscaPessoa['dados']['pessoa_tipo']) {
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
                                            <option value="1000" selected>Profissão não informada</option>
                                            <?php
                                            $buscaProfissao = $profissaoController->ListarProfissoes();
                                            if ($buscaProfissao['status'] === 'success') {
                                                foreach ($buscaProfissao['dados'] as $profissao) {
                                                    if ($profissao['pessoas_profissoes_id'] == $buscaPessoa['dados']['pessoa_profissao']) {
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
                                        <input type="text" class="form-control form-control-sm" name="cargo" placeholder="Cargo (Diretor, assessor, coordenador....)" value="<?php echo $buscaPessoa['dados']['pessoa_cargo']; ?>">
                                    </div>
                                    <?php

                                    if ($buscaPessoa['dados']['pessoa_tipo'] != 1008 && $buscaPessoa['dados']['pessoa_tipo'] != 1009) {
                                            echo ' <div class="col-md-3 col-12">
                                            <div class="file-upload">
                                                <input type="file" id="file-input" name="foto" style="display: none;" />
                                                <button id="file-button" type="button" class="btn btn-primary btn-sm"><i class="fa-regular fa-image"></i> Escolher Foto</button>
                                            </div>
                                        </div>';
                                    }

                                    ?>
                                   
                                    <div class="col-md-12 col-12">
                                        <textarea class="form-control form-control-sm" name="informacoes" rows="5" placeholder="Informações importantes dessa pessoa"><?php echo $buscaPessoa['dados']['pessoa_informacoes']; ?></textarea>
                                    </div>

                                    <div class="col-md-2 col-6">

                                        <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
                                        <button type="submit" class="btn btn-danger btn-sm" name="btn_apagar"><i class="fa-solid fa-trash"></i> Apagar</button>
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
        });

        function carregarEstados() {
            $.getJSON('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome', function(data) {
                const selectEstado = $('#estado');
                selectEstado.empty();
                selectEstado.append('<option value="" selected>UF</option>');
                data.forEach(estado => {
                    if (estado.sigla === "<?php echo $buscaPessoa['dados']['pessoa_estado'] ?>") {
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
                    if (municipio.nome === "<?php echo $buscaPessoa['dados']['pessoa_municipio'] ?>") {
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

        $('#file-button').on('click', function() {
            $('#file-input').click();
        });

        $('#file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $('#file-button').html(fileName ? '<i class="fa-regular fa-circle-check"></i> Foto selecionada' : 'Nenhuma foto selecionada');
        });


        $('button[name="btn_apagar"]').on('click', function(event) {
            const confirmacao = confirm("Tem certeza que deseja apagar esta pessoa?");
            if (!confirmacao) {
                event.preventDefault();
            }
        });

        $('button[name="btn_salvar"]').on('click', function(event) {
            const confirmacao = confirm("Tem certeza que deseja atualizar esta pessoa?");
            if (!confirmacao) {
                event.preventDefault();
            }
        });
    </script>
</body>

</html>