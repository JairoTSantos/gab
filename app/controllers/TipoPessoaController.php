<?php

require_once dirname(__DIR__) . '/models/TipoPessoaModel.php';

class PessoaTipoController {

    private $tipoPessoaModel;
    private $usuario_id;

    public function __construct() {
        $this->tipoPessoaModel = new TipoPessoaModel();
        $this->usuario_id = $_SESSION['usuario_id']; 
    }

    public function NovoTipoPessoa($dados) {

        if (empty($dados['pessoa_tipo_nome'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $dados['pessoa_tipo_criado_por'] = $this->usuario_id;

        $result = $this->tipoPessoaModel->NovoTipoPessoa($dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Tipo de pessoa inserido com sucesso.'];
        }

        if ($result['status'] == 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Esse tipo já está inserido.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao inserir o tipo de pessoa.'];
        }
    }

    public function AtualizarTipoPessoa($id, $dados) {

        if (empty($dados['pessoa_tipo_nome'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $result = $this->tipoPessoaModel->AtualizarTipoPessoa($id, $dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Tipo de pessoa atualizado com sucesso.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao atualizar o tipo de pessoa.'];
        }
    }

    public function ListarTiposPessoas() {

        $result = $this->tipoPessoaModel->ListarTiposPessoas();

        if ($result['status'] == 'success') {
            return $result;
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum tipo registrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar tipos de pessoas.'];
        }
    }

    public function BuscarTipoPessoa($coluna, $valor) {

        $result = $this->tipoPessoaModel->BuscarTipoPessoa($coluna, $valor);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum tipo registrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao buscar tipo de pessoa.'];
        }
    }

    public function ApagarTipoPessoa($id) {

        $resultDelete = $this->tipoPessoaModel->ApagarTipoPessoa($id);

        if ($resultDelete['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Tipo de pessoa apagado com sucesso.'];
        }

        if ($resultDelete['status'] == 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Esse tipo não pode ser apagado.'];
        }

        if ($resultDelete['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao apagar o tipo de pessoa.'];
        }
    }
}
