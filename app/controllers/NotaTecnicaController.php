<?php

require_once dirname(__DIR__) . '/models/NotaTecnicaModel.php';

class NotaTecnicaController {

    private $notaTecnicaModel;
    private $usuario_id;

    public function __construct() {
        $this->notaTecnicaModel = new NotaTecnicaModel();
        $this->usuario_id = $_SESSION['usuario_id']; // pegar do session
    }

    public function NovaNotaTecnica($dados) {

        if (empty($dados['nota_proposicao']) || empty($dados['nota_titulo']) || empty($dados['nota_resumo']) || empty($dados['nota_texto'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $dados['nota_criada_por'] = $this->usuario_id;

        $result = $this->notaTecnicaModel->NovaNotaTecnica($dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Nota Técnica inserida com sucesso.'];
        }

        if ($result['status'] == 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Essa nota técnica já está inserida.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao inserir a nota técnica.'];
        }
    }

    public function AtualizarNotaTecnica($id, $dados) {

        if (empty($dados['nota_titulo']) || empty($dados['nota_resumo']) || empty($dados['nota_texto'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $dados['nota_criada_por'] = $this->usuario_id;

        $result = $this->notaTecnicaModel->AtualizarNotaTecnica($id, $dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Nota Técnica atualizada com sucesso.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao atualizar a nota técnica.'];
        }
    }

    public function ListarNotasTecnicas() {

        $result = $this->notaTecnicaModel->ListarNotasTecnicas();

        if ($result['status'] == 'success') {
            return $result;
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhuma nota técnica registrada.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar notas técnicas.'];
        }
    }

    public function BuscarNotaTecnica($coluna, $valor) {

        $result = $this->notaTecnicaModel->BuscarNotaTecnica($coluna, $valor);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhuma nota técnica encontrada.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao buscar a nota técnica.'];
        }
    }

    public function ApagarNotaTecnica($id) {

        $resultDelete = $this->notaTecnicaModel->ApagarNotaTecnica($id);

        if ($resultDelete['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Nota técnica apagada com sucesso.'];
        }

        if ($resultDelete['status'] == 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Essa nota técnica não pode ser apagada.'];
        }

        if ($resultDelete['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao apagar a nota técnica.'];
        }
    }
}
