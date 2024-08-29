<?php

require_once dirname(__DIR__) . '/models/UsuarioModel.php';

class UsuarioController {

    private $usuarioModel;
    private $usuario_nivel;

    public function __construct() {
        $this->usuario_nivel = isset($_SESSION['usuario_nivel']) ? $_SESSION['usuario_nivel'] : null;

        $this->usuarioModel = new UsuarioModel();
    }

    public function novoUsuario($dados) {

        if ($this->usuario_nivel != 1) {
            return ['status' => 'error', 'message' => 'Você não tem autorização para inserir novos usuários.'];
        }

        $dados['usuario_nome'] = isset($dados['usuario_nome']) ? htmlspecialchars(trim($dados['usuario_nome'])) : '';
        $dados['usuario_email'] = isset($dados['usuario_email']) ? htmlspecialchars(trim($dados['usuario_email'])) : '';
        $dados['usuario_telefone'] = isset($dados['usuario_telefone']) ? htmlspecialchars(trim($dados['usuario_telefone'])) : '';
        $dados['usuario_senha'] = isset($dados['usuario_senha']) ? trim($dados['usuario_senha']) : '';
        $dados['usuario_nivel'] = isset($dados['usuario_nivel']) ? (int) $dados['usuario_nivel'] : 0;
        $dados['usuario_ativo'] = isset($dados['usuario_ativo']) ? (int) $dados['usuario_ativo'] : 0;
        $dados['usuario_aniversario'] = isset($dados['usuario_aniversario']) ? htmlspecialchars(trim($dados['usuario_aniversario'])) : '';

        if (!filter_var($dados['usuario_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_email', 'message' => 'Formato de email inválido.'];
        }

        if (empty($dados['usuario_nome']) || empty($dados['usuario_email']) || empty($dados['usuario_telefone']) || empty($dados['usuario_senha']) || !isset($dados['usuario_nivel']) || !isset($dados['usuario_ativo']) || empty($dados['usuario_aniversario'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $resultado = $this->usuarioModel->novoUsuario($dados);

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Usuário inserido com sucesso.'];
        }

        if ($resultado['status'] === 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Esse usuário já está cadastrado.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function atualizarUsuario($id, $dados) {

        if ($this->usuario_nivel != 1) {
            return ['status' => 'error', 'message' => 'Você não tem autorização para atualizar esse usuário.'];
        }

        $dados['usuario_nome'] = isset($dados['usuario_nome']) ? htmlspecialchars(trim($dados['usuario_nome'])) : '';
        $dados['usuario_email'] = isset($dados['usuario_email']) ? htmlspecialchars(trim($dados['usuario_email'])) : '';
        $dados['usuario_telefone'] = isset($dados['usuario_telefone']) ? htmlspecialchars(trim($dados['usuario_telefone'])) : '';
        $dados['usuario_senha'] = isset($dados['usuario_senha']) ? trim($dados['usuario_senha']) : '';
        $dados['usuario_nivel'] = isset($dados['usuario_nivel']) ? (int) $dados['usuario_nivel'] : 0;
        $dados['usuario_ativo'] = isset($dados['usuario_ativo']) ? (int) $dados['usuario_ativo'] : 0;
        $dados['usuario_aniversario'] = isset($dados['usuario_aniversario']) ? htmlspecialchars(trim($dados['usuario_aniversario'])) : '';

        if (!filter_var($dados['usuario_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_email', 'message' => 'Formato de email inválido.'];
        }

        if (empty($dados['usuario_nome']) || empty($dados['usuario_email']) || empty($dados['usuario_telefone']) || !isset($dados['usuario_nivel']) || !isset($dados['usuario_ativo']) || empty($dados['usuario_aniversario'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $resultado = $this->usuarioModel->atualizarUsuario($id, $dados);

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Usuário atualizado com sucesso.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function apagarUsuario($id) {

        if ($this->usuario_nivel != 1) {
            return ['status' => 'error', 'message' => 'Você não tem autorização para apagar um usuário.'];
        }

        $resultado = $this->usuarioModel->apagarUsuario($id);

        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return ['status' => 'invalid_id', 'message' => 'ID do usuário é inválido.'];
        }

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Usuário apagado com sucesso. Aguarde...'];
        }

        if ($resultado['status'] === 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Esse usuário não pode ser apagado pois ele é referenciado por outros itens.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function listarUsuarios($pagina = 1, $itens = 10, $ordernarPor = 'usuario_nome', $order = 'ASC') {
        $resultado = $this->usuarioModel->listarUsuarios($pagina, $itens, $ordernarPor, $order);
        return $resultado;
    }

    public function buscarUsuario($coluna, $valor) {
        $resultado = $this->usuarioModel->buscarUsuario($coluna, $valor);
        return $resultado;
    }
}
