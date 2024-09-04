<?php

require_once dirname(__DIR__) . '/models/PessoaModel.php';

class PessoaController {

    private $pessoaModel;
    private $usuario_id;
    private $usuario_nivel;

    public function __construct() {;
        $this->usuario_id = $_SESSION['usuario_id'];
        $this->pessoaModel = new PessoaModel();
        $this->usuario_nivel = isset($_SESSION['usuario_nivel']) ? $_SESSION['usuario_nivel'] : null;
    }

    public function novaPessoa($dados) {

        $dados['pessoa_nome'] = isset($dados['pessoa_nome']) ? htmlspecialchars(trim($dados['pessoa_nome'])) : '';
        $dados['pessoa_email'] = isset($dados['pessoa_email']) ? htmlspecialchars(trim($dados['pessoa_email'])) : '';
        $dados['pessoa_aniversario'] = isset($dados['pessoa_aniversario']) ? htmlspecialchars(trim($dados['pessoa_aniversario'])) : '';
        $dados['pessoa_telefone'] = isset($dados['pessoa_telefone']) ? htmlspecialchars(trim($dados['pessoa_telefone'])) : '';
        $dados['pessoa_endereco'] = isset($dados['pessoa_endereco']) ? htmlspecialchars(trim($dados['pessoa_endereco'])) : '';
        $dados['pessoa_bairro'] = isset($dados['pessoa_bairro']) ? htmlspecialchars(trim($dados['pessoa_bairro'])) : '';
        $dados['pessoa_municipio'] = isset($dados['pessoa_municipio']) ? htmlspecialchars(trim($dados['pessoa_municipio'])) : '';
        $dados['pessoa_estado'] = isset($dados['pessoa_estado']) ? htmlspecialchars(trim($dados['pessoa_estado'])) : '';
        $dados['pessoa_cep'] = isset($dados['pessoa_cep']) ? htmlspecialchars(trim($dados['pessoa_cep'])) : '';
        $dados['pessoa_sexo'] = isset($dados['pessoa_sexo']) ? htmlspecialchars(trim($dados['pessoa_sexo'])) : '';
        $dados['pessoa_facebook'] = isset($dados['pessoa_facebook']) ? htmlspecialchars(trim($dados['pessoa_facebook'])) : '';
        $dados['pessoa_instagram'] = isset($dados['pessoa_instagram']) ? htmlspecialchars(trim($dados['pessoa_instagram'])) : '';
        $dados['pessoa_x'] = isset($dados['pessoa_x']) ? htmlspecialchars(trim($dados['pessoa_x'])) : '';
        $dados['pessoa_tipo'] = isset($dados['pessoa_tipo']) ? (int) $dados['pessoa_tipo'] : 0;
        $dados['pessoa_profissao'] = isset($dados['pessoa_profissao']) ? (int) $dados['pessoa_profissao'] : 0;
        $dados['pessoa_cargo'] = isset($dados['pessoa_cargo']) ? htmlspecialchars(trim($dados['pessoa_cargo'])) : '';
        $dados['pessoa_orgao'] = isset($dados['pessoa_orgao']) ? htmlspecialchars(trim($dados['pessoa_orgao'])) : '';
        $dados['pessoa_informacoes'] = isset($dados['pessoa_informacoes']) ? htmlspecialchars(trim($dados['pessoa_informacoes'])) : '';
        $dados['pessoa_criada_por'] = $this->usuario_id;

        if (empty($dados['pessoa_nome']) || empty($dados['pessoa_email']) || empty($dados['pessoa_municipio']) || empty($dados['pessoa_estado'])) {
            return ['status' => 'error', 'message' => 'Preencha todos os campos.'];
        }

        $resultado = $this->pessoaModel->novaPessoa($dados);

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Pessoa inserida com sucesso.'];
        }

        if ($resultado['status'] === 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Essa pessoa já está cadastrada.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function atualizarPessoa($id, $dados) {

        $dados['pessoa_nome'] = isset($dados['pessoa_nome']) ? htmlspecialchars(trim($dados['pessoa_nome'])) : '';
        $dados['pessoa_email'] = isset($dados['pessoa_email']) ? htmlspecialchars(trim($dados['pessoa_email'])) : '';
        $dados['pessoa_aniversario'] = isset($dados['pessoa_aniversario']) ? htmlspecialchars(trim($dados['pessoa_aniversario'])) : '';
        $dados['pessoa_telefone'] = isset($dados['pessoa_telefone']) ? htmlspecialchars(trim($dados['pessoa_telefone'])) : '';
        $dados['pessoa_endereco'] = isset($dados['pessoa_endereco']) ? htmlspecialchars(trim($dados['pessoa_endereco'])) : '';
        $dados['pessoa_bairro'] = isset($dados['pessoa_bairro']) ? htmlspecialchars(trim($dados['pessoa_bairro'])) : '';
        $dados['pessoa_municipio'] = isset($dados['pessoa_municipio']) ? htmlspecialchars(trim($dados['pessoa_municipio'])) : '';
        $dados['pessoa_estado'] = isset($dados['pessoa_estado']) ? htmlspecialchars(trim($dados['pessoa_estado'])) : '';
        $dados['pessoa_cep'] = isset($dados['pessoa_cep']) ? htmlspecialchars(trim($dados['pessoa_cep'])) : '';
        $dados['pessoa_sexo'] = isset($dados['pessoa_sexo']) ? htmlspecialchars(trim($dados['pessoa_sexo'])) : '';
        $dados['pessoa_facebook'] = isset($dados['pessoa_facebook']) ? htmlspecialchars(trim($dados['pessoa_facebook'])) : '';
        $dados['pessoa_instagram'] = isset($dados['pessoa_instagram']) ? htmlspecialchars(trim($dados['pessoa_instagram'])) : '';
        $dados['pessoa_x'] = isset($dados['pessoa_x']) ? htmlspecialchars(trim($dados['pessoa_x'])) : '';
        $dados['pessoa_tipo'] = isset($dados['pessoa_tipo']) ? (int) $dados['pessoa_tipo'] : 0;
        $dados['pessoa_profissao'] = isset($dados['pessoa_profissao']) ? (int) $dados['pessoa_profissao'] : 0;
        $dados['pessoa_cargo'] = isset($dados['pessoa_cargo']) ? htmlspecialchars(trim($dados['pessoa_cargo'])) : '';
        $dados['pessoa_orgao'] = isset($dados['pessoa_orgao']) ? htmlspecialchars(trim($dados['pessoa_orgao'])) : '';
        $dados['pessoa_informacoes'] = isset($dados['pessoa_informacoes']) ? htmlspecialchars(trim($dados['pessoa_informacoes'])) : '';
        $dados['pessoa_criado_por'] = $this->usuario_id;

        if (empty($dados['pessoa_nome']) || empty($dados['pessoa_email']) || empty($dados['pessoa_municipio']) || empty($dados['pessoa_estado'])) {
            return ['status' => 'error', 'message' => 'Preencha todos os campos.'];
        }

        $resultado = $this->pessoaModel->atualizarPessoa($id, $dados);

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Pessoa atualizada com sucesso.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function apagarPessoa($id) {

        $resultado = $this->pessoaModel->apagarPessoa($id);

        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return ['status' => 'invalid_id', 'message' => 'ID da pessoa é inválido.'];
        }

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Pessoa apagada com sucesso. Aguarde...'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function listarPessoas($pagina = 1, $itens = 10, $ordernarPor = 'pessoa_nome', $order = 'ASC', $termo = null, $filtro = true) {
        $resultado = $this->pessoaModel->listarPessoas($pagina, $itens, $ordernarPor, $order, $termo, $filtro);
        return $resultado;
    }

    public function buscarPessoa($coluna, $valor) {
        $resultado = $this->pessoaModel->buscarPessoa($coluna, $valor);
        return $resultado;
    }
    


    public function novaTipoPessoa($dados) {

        ##verfiicar a necessidade dessa verificacao
        if ($this->usuario_nivel != 1) {
            return ['status' => 'error', 'message' => 'Você não tem autorização para inserir novos usuários.'];
        }

        $dados['pessoa_tipo_nome'] = isset($dados['pessoa_tipo_nome']) ? htmlspecialchars(trim($dados['pessoa_tipo_nome'])) : '';
        $dados['pessoa_tipo_descricao'] = isset($dados['pessoa_tipo_descricao']) ? htmlspecialchars(trim($dados['pessoa_tipo_descricao'])) : '';

        $dados['pessoa_tipo_criado_por'] = $this->usuario_id;

        if (empty($dados['pessoa_tipo_nome'])) {
            return ['status' => 'error', 'message' => 'Preencha todos os campos.'];
        }

        $resultado = $this->pessoaModel->novoTipoPessoa($dados);

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Tipo de pessoa inserido com sucesso.'];
        }

        if ($resultado['status'] === 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Esse tipo já está cadastrado.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function listarTiposPessoas() {
        $resultado = $this->pessoaModel->listarTiposPessoas();
        return $resultado;
    }

    public function apagarTiposPessoas($id) {

        $resultado = $this->pessoaModel->apagarTipoPessoa($id);

        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return ['status' => 'invalid_id', 'message' => 'ID do tipo é inválido.'];
        }

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Tipo de pessoa apagado com sucesso.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }



    public function novaProfissaoPessoa($dados) {

        ##verfiicar a necessidade dessa verificacao
        if ($this->usuario_nivel != 1) {
            return ['status' => 'error', 'message' => 'Você não tem autorização para inserir novos usuários.'];
        }

        $dados['pessoas_profissoes_nome'] = isset($dados['pessoas_profissoes_nome']) ? htmlspecialchars(trim($dados['pessoas_profissoes_nome'])) : '';
        $dados['pessoas_profissoes_descricao'] = isset($dados['pessoas_profissoes_descricao']) ? htmlspecialchars(trim($dados['pessoas_profissoes_descricao'])) : '';
        $dados['pessoas_profissoes_criado_por'] = $this->usuario_id;

        if (empty($dados['pessoas_profissoes_nome'])) {
            return ['status' => 'error', 'message' => 'Preencha todos os campos.'];
        }

        $resultado = $this->pessoaModel->novaProfissaoPessoa($dados);

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Profissão inserida com sucesso.'];
        }

        if ($resultado['status'] === 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Profissão tipo já está cadastrado.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function listarProfissoesPessoas() {
        $resultado = $this->pessoaModel->listarProfissoesPessoas();
        return $resultado;
    }

    public function apagarProfissaoPessoa($id) {

        $resultado = $this->pessoaModel->apagarProfissaoPessoa($id);

        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return ['status' => 'invalid_id', 'message' => 'ID da profissão é inválido.'];
        }

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Profissão apagada com sucesso.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }
}
