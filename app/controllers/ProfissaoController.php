<?php

require_once dirname(__DIR__) . '/models/ProfissaoModel.php';

class ProfissaoController {

    private $profissaoModel;
    private $usuario_id;

    public function __construct() {
        $this->profissaoModel = new ProfissaoModel();
        $this->usuario_id = $_SESSION['usuario_id']; // pegar do session
    }

    public function NovaProfissao($dados) {

        if (empty($dados['pessoas_profissoes_nome'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $dados['pessoas_profissoes_criado_por'] = $this->usuario_id;

        $result = $this->profissaoModel->NovaProfissao($dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Profissão inserida com sucesso.'];
        }

        if ($result['status'] == 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Essa profissão já está inserida.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao inserir a profissão.'];
        }
    }

    public function AtualizarProfissao($id, $dados) {

        if (empty($dados['pessoas_profissoes_nome'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $dados['pessoas_profissoes_criado_por'] = $this->usuario_id;

        $result = $this->profissaoModel->AtualizarProfissao($id, $dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Profissão atualizada com sucesso.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao atualizar a profissão.'];
        }
    }

    public function ListarProfissoes() {

        $result = $this->profissaoModel->ListarProfissoes();

        if ($result['status'] == 'success') {
            return $result;
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhuma profissão registrada.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar profissões.'];
        }
    }

    public function BuscarProfissao($coluna, $valor) {

        $result = $this->profissaoModel->BuscarProfissao($coluna, $valor);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhuma profissão encontrada.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao buscar a profissão.'];
        }
    }

    public function ApagarProfissao($id) {

        $resultDelete = $this->profissaoModel->ApagarProfissao($id);

        if ($resultDelete['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Profissão apagada com sucesso.'];
        }

        if ($resultDelete['status'] == 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Essa profissão não pode ser apagada.'];
        }

        if ($resultDelete['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao apagar a profissão.'];
        }
    }
}
