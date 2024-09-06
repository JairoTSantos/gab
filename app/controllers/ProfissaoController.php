<?php

require_once dirname(__DIR__) . '/models/ProfissaoModel.php';

class ProfissaoController {

    private $profissaoModel;
    private $usuario_id;

    public function __construct() {
        $this->profissaoModel = new ProfissaoModel();
        $this->usuario_id = 1000; // Pegado da sessão ou configurado conforme necessário
    }

    public function NovaProfissao($dados) {
        $dados['pessoas_profissoes_nome'] = isset($dados['pessoas_profissoes_nome']) ? trim($dados['pessoas_profissoes_nome']) : '';
        $dados['pessoas_profissoes_descricao'] = isset($dados['pessoas_profissoes_descricao']) ? trim($dados['pessoas_profissoes_descricao']) : '';
        $dados['pessoas_profissoes_criado_por'] = $this->usuario_id;

        if (empty($dados['pessoas_profissoes_nome']) || !isset($dados['pessoas_profissoes_criado_por'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos obrigatórios.'];
        }

        return $this->profissaoModel->NovaProfissao($dados);
    }

    public function AtualizarProfissao($id, $dados) {
        $dados['pessoas_profissoes_nome'] = isset($dados['pessoas_profissoes_nome']) ? trim($dados['pessoas_profissoes_nome']) : '';
        $dados['pessoas_profissoes_descricao'] = isset($dados['pessoas_profissoes_descricao']) ? trim($dados['pessoas_profissoes_descricao']) : '';
        $dados['pessoas_profissoes_criado_por'] = $this->usuario_id;

        if (empty($dados['pessoas_profissoes_nome']) || !isset($dados['pessoas_profissoes_criado_por'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos obrigatórios.'];
        }

        return $this->profissaoModel->AtualizarProfissao($id, $dados);
    }

    public function ApagarProfissao($id) {
        return $this->profissaoModel->ApagarProfissao($id);
    }

    public function ListarProfissoes() {
        return $this->profissaoModel->ListarProfissoes();
    }

    public function BuscarProfissao($coluna, $valor) {
        if (!in_array($coluna, ['pessoas_profissoes_id', 'pessoas_profissoes_nome'])) {
            return ['status' => 'invalid_column', 'message' => 'A coluna selecionada é inválida'];
        }

        return $this->profissaoModel->BuscarProfissao($coluna, $valor);
    }
}
