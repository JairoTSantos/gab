<?php

require_once dirname(__DIR__) . '/models/TipoClippingModel.php';

class ClippingTipoController {

    private $clippingTipoModel;
    private $usuario_id;

    public function __construct() {
        $this->clippingTipoModel = new TipoClippingModel();
        $this->usuario_id = $_SESSION['usuario_id']; 
    }

    public function NovoClippingTipo($dados) {

        if (empty($dados['clipping_tipo_nome'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $dados['clipping_tipo_criado_por'] = $this->usuario_id;

        $result = $this->clippingTipoModel->NovoClippingTipo($dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Tipo de clipping inserido com sucesso.'];
        }

        if ($result['status'] == 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Esse tipo de clipping já está inserido.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao inserir o tipo de clipping.'];
        }
    }

    public function AtualizarClippingTipo($id, $dados) {

        if (empty($dados['clipping_tipo_nome'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $result = $this->clippingTipoModel->AtualizarClippingTipo($id, $dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Tipo de clipping atualizado com sucesso.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao atualizar o tipo de clipping.'];
        }
    }

    public function ListarClippingTipos() {

        $result = $this->clippingTipoModel->ListarClippingTipos();

        if ($result['status'] == 'success') {
            return $result;
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum tipo de clipping registrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar tipos de clipping.'];
        }
    }

    public function BuscarClippingTipo($coluna, $valor) {

        $result = $this->clippingTipoModel->BuscarClippingTipo($coluna, $valor);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum tipo de clipping registrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao buscar tipo de clipping.'];
        }
    }

    public function ApagarClippingTipo($id) {

        $resultDelete = $this->clippingTipoModel->ApagarClippingTipo($id);

        if ($resultDelete['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Tipo de clipping apagado com sucesso.'];
        }

        if ($resultDelete['status'] == 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Esse tipo de clipping não pode ser apagado.'];
        }

        if ($resultDelete['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao apagar o tipo de clipping.'];
        }
    }
}
