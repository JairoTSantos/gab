<?php

require_once dirname(__DIR__) . '/models/NotaModel.php';

class NotaController {

    private $notaModel;
    private $usuario_id;

    public function __construct() {
        $this->usuario_id = $_SESSION['usuario_id'];
        $this->notaModel = new NotaModel();
    }

    public function novaNota($dados) {

        $dados['nota_proposicao'] = isset($dados['nota_proposicao']) ? (int) $dados['nota_proposicao'] : 0;
        $dados['nota_titulo'] = isset($dados['nota_titulo']) ? htmlspecialchars(trim($dados['nota_titulo'])) : '';
        $dados['nota_resumo'] = isset($dados['nota_resumo']) ? htmlspecialchars(trim($dados['nota_resumo'])) : '';
        $dados['nota_texto'] = isset($dados['nota_texto']) ? htmlspecialchars(trim($dados['nota_texto'])) : '';
        $dados['nota_criada_por'] =  $this->usuario_id;

        if (empty($dados['nota_titulo']) || empty($dados['nota_resumo']) || empty($dados['nota_texto'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $resultado = $this->notaModel->novaNota($dados);

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Nota inserida com sucesso.'];
        }

        if ($resultado['status'] === 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Essa nota já está cadastrada.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function atualizarNota($id, $dados) {

        $dados['nota_proposicao'] = isset($dados['nota_proposicao']) ? (int) $dados['nota_proposicao'] : 0;
        $dados['nota_titulo'] = isset($dados['nota_titulo']) ? htmlspecialchars(trim($dados['nota_titulo'])) : '';
        $dados['nota_resumo'] = isset($dados['nota_resumo']) ? htmlspecialchars(trim($dados['nota_resumo'])) : '';
        $dados['nota_texto'] = isset($dados['nota_texto']) ? htmlspecialchars(trim($dados['nota_texto'])) : '';

        if (empty($dados['nota_titulo']) || empty($dados['nota_resumo']) || empty($dados['nota_texto'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $resultado = $this->notaModel->atualizarNota($id, $dados);

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Nota atualizada com sucesso.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function apagarNota($id) {

        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return ['status' => 'invalid_id', 'message' => 'ID da nota é inválido.'];
        }

        $resultado = $this->notaModel->apagarNota($id);

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Nota apagada com sucesso.'];
        }

        if ($resultado['status'] === 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Essa nota não pode ser apagada pois ela é referenciada por outros itens.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function listarNotas($pagina = 1, $itens = 10, $ordernarPor = 'nota_criada_em', $order = 'DESC') {
        $resultado = $this->notaModel->listarNotas($pagina, $itens, $ordernarPor, $order);
        return $resultado;
    }

    public function buscarNota($coluna, $valor) {
        $resultado = $this->notaModel->buscarNota($coluna, $valor);
        return $resultado;
    }
}
