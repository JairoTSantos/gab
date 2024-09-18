<?php

require_once dirname(__DIR__) . '/models/StatusPostagensModel.php';

class StatusPostagemController {

    private $statusPostagemModel;
    private $usuario_id;

    public function __construct() {
        $this->statusPostagemModel = new StatusPostagensModel();
        $this->usuario_id = $_SESSION['usuario_id'];
    }

    public function NovoStatusPostagem($dados) {

        if (empty($dados['postagem_status_nome'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $dados['postagem_status_criado_por'] = $this->usuario_id;

        $result = $this->statusPostagemModel->NovoStatusPostagem($dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Status de postagem inserido com sucesso.'];
        }

        if ($result['status'] == 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Esse status já está inserido.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao inserir o status de postagem.'];
        }
    }

    public function AtualizarStatusPostagem($id, $dados) {

        if (empty($dados['postagem_status_nome'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $result = $this->statusPostagemModel->AtualizarStatusPostagem($id, $dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Status de postagem atualizado com sucesso.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao atualizar o status de postagem.'];
        }
    }

    public function ListarStatusPostagens() {

        $result = $this->statusPostagemModel->ListarStatusPostagens();

        if ($result['status'] == 'success') {
            return $result;
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum status registrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar status de postagens.'];
        }
    }

    public function BuscarStatusPostagem($coluna, $valor) {

        $result = $this->statusPostagemModel->BuscarStatusPostagem($coluna, $valor);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum status registrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao buscar status de postagem.'];
        }
    }

    public function ApagarStatusPostagem($id) {

        $resultDelete = $this->statusPostagemModel->ApagarStatusPostagem($id);

        if ($resultDelete['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Status de postagem apagado com sucesso.'];
        }

        if ($resultDelete['status'] == 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Esse status não pode ser apagado devido a conflitos.'];
        }

        if ($resultDelete['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao apagar o status de postagem.'];
        }
    }
}
