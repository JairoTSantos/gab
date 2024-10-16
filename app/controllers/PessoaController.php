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

        if (!in_array($coluna, ['pessoa_id', 'pessoa_email', 'pessoa_orgao'])) {
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
}
