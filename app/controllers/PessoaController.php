<?php

require_once dirname(__DIR__) . '/models/PessoaModel.php';
require_once dirname(__DIR__) . '/core/UploadFile.php';

class PessoaController {

    private $pessoaModel;
    private $uploadFile;
    private $pasta_foto;
    private $usuario_id;

    public function __construct() {
        $this->pessoaModel = new PessoaModel();
        $this->uploadFile = new UploadFile();
        $this->pasta_foto = '/public/arquivos/fotos_pessoas/';
        $this->usuario_id = 1000; //pegaro do session
    }


    public function NovoPessoa($dados) {

        $dados['pessoa_nome'] = isset($dados['pessoa_nome']) ? trim($dados['pessoa_nome']) : '';
        $dados['pessoa_aniversario'] = isset($dados['pessoa_aniversario']) ? trim($dados['pessoa_aniversario']) : '';
        $dados['pessoa_email'] = isset($dados['pessoa_email']) ? trim($dados['pessoa_email']) : '';
        $dados['pessoa_telefone'] = isset($dados['pessoa_telefone']) ? trim($dados['pessoa_telefone']) : '';
        $dados['pessoa_endereco'] = isset($dados['pessoa_endereco']) ? trim($dados['pessoa_endereco']) : '';
        $dados['pessoa_bairro'] = isset($dados['pessoa_bairro']) ? trim($dados['pessoa_bairro']) : '';
        $dados['pessoa_municipio'] = isset($dados['pessoa_municipio']) ? trim($dados['pessoa_municipio']) : '';
        $dados['pessoa_estado'] = isset($dados['pessoa_estado']) ? trim($dados['pessoa_estado']) : '';
        $dados['pessoa_cep'] = isset($dados['pessoa_cep']) ? trim($dados['pessoa_cep']) : '';
        $dados['pessoa_sexo'] = isset($dados['pessoa_sexo']) ? trim($dados['pessoa_sexo']) : '';
        $dados['pessoa_facebook'] = isset($dados['pessoa_facebook']) ? trim($dados['pessoa_facebook']) : '';
        $dados['pessoa_instagram'] = isset($dados['pessoa_instagram']) ? trim($dados['pessoa_instagram']) : '';
        $dados['pessoa_x'] = isset($dados['pessoa_x']) ? trim($dados['pessoa_x']) : '';
        $dados['pessoa_informacoes'] = isset($dados['pessoa_informacoes']) ? trim($dados['pessoa_informacoes']) : '';
        $dados['pessoa_profissao'] = isset($dados['pessoa_profissao']) ? (int) $dados['pessoa_profissao'] : 1000;
        $dados['pessoa_cargo'] = isset($dados['pessoa_cargo']) ? trim($dados['pessoa_cargo']) : '';
        $dados['pessoa_tipo'] = isset($dados['pessoa_tipo']) ? (int) $dados['pessoa_tipo'] : 1000;
        $dados['pessoa_orgao'] = isset($dados['pessoa_orgao']) ? (int) $dados['pessoa_orgao'] : 1000;
        $dados['pessoa_criada_por'] = $this->usuario_id;


        if (!filter_var($dados['pessoa_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_email', 'message' => 'Formato de email inválido.'];
        }

        if (empty($dados['pessoa_nome']) || empty($dados['pessoa_aniversario']) || empty($dados['pessoa_email']) || empty($dados['pessoa_municipio']) || empty($dados['pessoa_estado']) || !isset($dados['pessoa_profissao']) || !isset($dados['pessoa_tipo']) || !isset($dados['pessoa_orgao']) || !isset($dados['pessoa_criada_por'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos obrigatórios.'];
        }


        if (isset($dados['foto']['tmp_name']) && !empty($dados['foto']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $this->pasta_foto, $dados['foto']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['pessoa_foto'] = $this->pasta_foto . $uploadResult['filename'];
            } else {
                return $uploadResult;
            }
        }

        $result =  $this->pessoaModel->NovaPessoa($dados);

        if ($result['status'] == 'error' || $result['status'] == 'duplicated') {
            if (isset($dados['pessoa_foto']) && !empty($dados['pessoa_foto'])) {
                unlink('..' . $dados['pessoa_foto']);
            }
        }

        return $result;
    }


    public function AtualizarPessoa($id, $dados) {


        $dados['pessoa_nome'] = isset($dados['pessoa_nome']) ? trim($dados['pessoa_nome']) : '';
        $dados['pessoa_aniversario'] = isset($dados['pessoa_aniversario']) ? trim($dados['pessoa_aniversario']) : '';
        $dados['pessoa_email'] = isset($dados['pessoa_email']) ? trim($dados['pessoa_email']) : '';
        $dados['pessoa_telefone'] = isset($dados['pessoa_telefone']) ? trim($dados['pessoa_telefone']) : '';
        $dados['pessoa_endereco'] = isset($dados['pessoa_endereco']) ? trim($dados['pessoa_endereco']) : '';
        $dados['pessoa_bairro'] = isset($dados['pessoa_bairro']) ? trim($dados['pessoa_bairro']) : '';
        $dados['pessoa_municipio'] = isset($dados['pessoa_municipio']) ? trim($dados['pessoa_municipio']) : '';
        $dados['pessoa_estado'] = isset($dados['pessoa_estado']) ? trim($dados['pessoa_estado']) : '';
        $dados['pessoa_cep'] = isset($dados['pessoa_cep']) ? trim($dados['pessoa_cep']) : '';
        $dados['pessoa_sexo'] = isset($dados['pessoa_sexo']) ? trim($dados['pessoa_sexo']) : '';
        $dados['pessoa_facebook'] = isset($dados['pessoa_facebook']) ? trim($dados['pessoa_facebook']) : '';
        $dados['pessoa_instagram'] = isset($dados['pessoa_instagram']) ? trim($dados['pessoa_instagram']) : '';
        $dados['pessoa_x'] = isset($dados['pessoa_x']) ? trim($dados['pessoa_x']) : '';
        $dados['pessoa_informacoes'] = isset($dados['pessoa_informacoes']) ? trim($dados['pessoa_informacoes']) : '';
        $dados['pessoa_profissao'] = isset($dados['pessoa_profissao']) ? (int) $dados['pessoa_profissao'] : 1000;
        $dados['pessoa_cargo'] = isset($dados['pessoa_cargo']) ? trim($dados['pessoa_cargo']) : '';
        $dados['pessoa_tipo'] = isset($dados['pessoa_tipo']) ? (int) $dados['pessoa_tipo'] : 1000;
        $dados['pessoa_orgao'] = isset($dados['pessoa_orgao']) ? (int) $dados['pessoa_orgao'] : 1000;

        if (!filter_var($dados['pessoa_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_email', 'message' => 'Formato de email inválido.'];
        }

        if (empty($dados['pessoa_nome']) || empty($dados['pessoa_aniversario']) || empty($dados['pessoa_email']) || empty($dados['pessoa_municipio']) || empty($dados['pessoa_estado']) || !isset($dados['pessoa_profissao']) || !isset($dados['pessoa_tipo']) || !isset($dados['pessoa_orgao']) || !isset($dados['pessoa_criada_por'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos obrigatórios.'];
        }

        if (isset($dados['foto']['tmp_name']) && !empty($dados['foto']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $this->pasta_foto, $dados['foto']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['pessoa_foto'] = $this->pasta_foto . $uploadResult['filename'];
            } else {
                return $uploadResult;
            }
        } else {
            $dados['usuario_foto'] = '';
        }

        $result = $this->pessoaModel->AtualizarPessoa($id, $dados);

        if ($result['status'] == 'error') {
            if (isset($dados['pessoa_foto']) && !empty($dados['pessoa_foto'])) {
                unlink('..' . $dados['pessoa_foto']);
            }
        }

        return $result;
    }


    public function BuscarPessoa($coluna, $valor) {

        if (!in_array($coluna, ['pessoa_id', 'pessoa_email', 'pessoa_municipio', 'pessoa_estado'])) {
            return ['status' => 'invalid_column', 'message' => 'A coluna selecionada é inválida'];
        }

        return $this->pessoaModel->BuscarPessoa($coluna, $valor);
    }


    public function ListarPessoas($itens = 10, $pagina = 1, $ordem = 'asc', $ordenarPor = 'pessoa_nome') {
        $ordernarPor = in_array($ordenarPor, ['pessoa_nome', 'pessoa_criada_por']) ? $ordenarPor : 'pessoa_nome';
        $order = strtoupper($ordem) === 'DESC' ? 'DESC' : 'ASC';
        return $this->pessoaModel->ListarPessoas($itens, $pagina, $ordem, $ordenarPor);
    }

    public function ApagarPessoa($id) {
        
        $result = $this->pessoaModel->BuscarPessoa('pessoa_id', $id);

        if ($result['status'] == 'success' && $result['dados']['pessoa_foto'] != null) {
            unlink('..' . $result['dados']['pessoa_foto']);
        }

        return $this->pessoaModel->ApagarPessoa($id);
    }
}
