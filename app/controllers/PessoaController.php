<?php

require_once dirname(__DIR__) . '/models/PessoaModel.php';
require_once dirname(__DIR__) . '/core/UploadFile.php';
require_once dirname(__DIR__) . '/core/GetJson.php';

class PessoaController {

    private $pessoaModel;
    private $usuario_id;
    private $uploadFile;
    private $pasta_foto;

    public function __construct() {
        $this->pessoaModel = new PessoaModel();

        $this->pasta_foto = '/public/arquivos/fotos_pessoas/';

        $this->uploadFile = new UploadFile();
        $this->usuario_id = $_SESSION['usuario_id'];
    }

    public function NovaPessoa($dados) {

        if (empty($dados['pessoa_nome']) || empty($dados['pessoa_email']) || empty($dados['pessoa_municipio']) || empty($dados['pessoa_estado']) || empty($dados['pessoa_tipo'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $dados['pessoa_criada_por'] = $this->usuario_id;


        if (isset($dados['foto']['tmp_name']) && !empty($dados['foto']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $this->pasta_foto, $dados['foto']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['pessoa_foto'] = $this->pasta_foto . $uploadResult['filename'];
            } else {
                if ($uploadResult['status'] == 'file_not_permitted') {
                    return ['status' => 'file_not_permitted', 'message' => 'Tipo de arquivo não permitido', 'permitted_files' => $uploadResult['permitted_files']];
                }
                if ($uploadResult['status'] == 'file_too_large') {
                    return ['status' => 'file_too_large', 'message' => 'O arquivo deve ter no máximo ' . $uploadResult['maximun_size']];
                }
                if ($uploadResult['status'] == 'error') {
                    return ['status' => 'error', 'message' => 'Erro ao fazer upload.'];
                }
            }
        }

        $result = $this->pessoaModel->NovaPessoa($dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Pessoa inserida com sucesso.'];
        }

        if ($result['status'] == 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Essa pessoa já está registrada.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao inserir a pessoa.'];
        }
    }

    public function AtualizarPessoa($id, $dados) {

        if (empty($dados['pessoa_nome']) || empty($dados['pessoa_email']) || empty($dados['pessoa_municipio'])  || empty($dados['pessoa_estado'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        if (isset($dados['foto']['tmp_name']) && !empty($dados['foto']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $this->pasta_foto, $dados['foto']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['pessoa_foto'] = $this->pasta_foto . $uploadResult['filename'];
            } else {
                if ($uploadResult['status'] == 'file_not_permitted') {
                    return ['status' => 'file_not_permitted', 'message' => 'Tipo de arquivo não permitido', 'permitted_files' => $uploadResult['permitted_files']];
                }
                if ($uploadResult['status'] == 'file_too_large') {
                    return ['status' => 'file_too_large', 'message' => 'O arquivo deve ter no máximo ' . $uploadResult['maximun_size']];
                }
                if ($uploadResult['status'] == 'error') {
                    return ['status' => 'error', 'message' => 'Erro ao fazer upload.'];
                }
            }
        } else {
            $dados['pessoa_foto'] = null;
        }

        $result = $this->pessoaModel->AtualizarPessoa($id, $dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Pessoa atualizada com sucesso. Aguarde...'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao atualizar a pessoa.'];
        }
    }

    public function ListarPessoas($itens = 10, $pagina = 1, $ordem = 'asc', $ordenarPor = 'pessoa_nome', $termo = '', $filtro = false) {
        $ordenarPor = in_array($ordenarPor, ['pessoa_id', 'pessoa_nome', 'pessoa_criada_em', 'pessoa_municipio', 'pessoa_estado']) ? $ordenarPor : 'pessoa_nome';
        $ordem = strtoupper($ordem) === 'DESC' ? 'DESC' : 'ASC';

        $result = $this->pessoaModel->ListarPessoas($itens, $pagina, $ordem, $ordenarPor, $termo, $filtro);

        if ($result['status'] == 'success') {
            return $result;
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhuma pessoa registrada.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar pessoas.'];
        }
    }

    public function BuscarPessoa($coluna, $valor) {

        if (!in_array($coluna, ['pessoa_id', 'pessoa_email'])) {
            return ['status' => 'invalid_column', 'message' => 'A coluna selecionada é inválida'];
        }

        $result = $this->pessoaModel->BuscarPessoa($coluna, $valor);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhuma pessoa registrada.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao buscar pessoa.'];
        }
    }

    public function BuscarAniversariante($mes, $dia = null) {


        $result = $this->pessoaModel->BuscarAniversariante($mes, $dia);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhuma pessoa registrada.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao buscar pessoa.'];
        }
    }

    public function ApagarPessoa($id) {

        $result = $this->pessoaModel->BuscarPessoa('pessoa_id', $id);

        $resultDelete = $this->pessoaModel->ApagarPessoa($id);

        if ($resultDelete['status'] == 'success') {
            if ($result['dados']['pessoa_foto'] != null) {
                unlink('..' . $result['dados']['pessoa_foto']);
            }
            return ['status' => 'success', 'message' => 'Pessoa apagada com sucesso. Aguarde...', 'dados' => $result['dados']];
        }

        if ($resultDelete['status'] == 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Essa pessoa não pode ser apagada.'];
        }

        if ($resultDelete['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao buscar pessoa.'];
        }
    }

    public function InserirDeputados() {
        $config = require dirname(__DIR__) . '/config/config.php';
        $depConfig = $config['deputado'];

        $dados = getJson('https://dadosabertos.camara.leg.br/arquivos/deputados/json/deputados.json');
        $depsArray = [];
        $contador = 0;

        foreach ($dados['dados'] as $dep) {
            if ($dep['idLegislaturaFinal'] == $depConfig['legislatura_atual']) {
                $deputado = [
                    'pessoa_nome' => $dep['nome'],
                    'pessoa_email' => $this->gerarEmail($dep['nome']),
                    'pessoa_aniversario' => $dep['dataNascimento'],
                    'pessoa_municipio' => $dep['municipioNascimento'],
                    'pessoa_estado' => $dep['ufNascimento'],
                    'pessoa_tipo' => 1001,
                    'pessoa_orgao' => 1001,
                    'pessoa_profissao' => 1041
                ];

                if (!empty($dep['urlRedeSocial'])) {
                    foreach ($dep['urlRedeSocial'] as $url) {
                        if (strpos($url, 'instagram.com') !== false) {
                            $deputado['pessoa_instagram'] = $url;
                        } elseif (strpos($url, 'facebook.com') !== false) {
                            $deputado['pessoa_facebook'] = $url;
                        } elseif (strpos($url, 'twitter.com') !== false) {
                            $deputado['pessoa_x'] = $url;
                        } elseif (strpos($url, 'x.com') !== false) {
                            $deputado['pessoa_x'] = $url;
                        }
                    }
                }

                if($dep['siglaSexo'] == 'M'){
                    $deputado['pessoa_sexo'] = 'Masculino';
                }else if($dep['siglaSexo'] == 'F'){
                    $deputado['pessoa_sexo'] = 'Feminino';
                }else{
                    $deputado['pessoa_sexo'] = 'Outro';
                }


                $depsArray[] = $deputado;
            }
        }

        $totalDeps = count($depsArray);

        foreach ($depsArray as $dep) {
            $this->NovaPessoa($dep);
            $contador++;
        }

        if ($totalDeps === $contador) {
            return ['status' => 'success', 'message' => 'Deputados inseridos com sucesso.'];
        }
    }

    public function gerarEmail($nome) {
        // Converte o nome para minúsculas
        $nome = strtolower($nome);
        // Remove o ponto da abreviação "Dr"
        $nome = preg_replace('/\bdr\.\s*/', 'dr ', $nome);
        // Converte caracteres especiais para ASCII
        $nome = iconv('UTF-8', 'ASCII//TRANSLIT', $nome);
        // Substitui espaços por pontos
        $nome = str_replace(' ', '.', $nome);
        // Remove caracteres não permitidos
        $nome = preg_replace('/[^a-z0-9.]/', '', $nome);
        
        return 'dep.' . $nome . '@camara.leg.br';
    }
    
}
