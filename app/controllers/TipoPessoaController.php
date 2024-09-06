<?php

require_once dirname(__DIR__) . '/models/TipoPessoaModel.php';

class TipoPessoaController {

    private $tipoPessoaModel;
    private $usuario_id;

    public function __construct() {
        $this->tipoPessoaModel = new TipoPessoaModel();
        $this->usuario_id = 1000; // Pegado da sessão ou configurado conforme necessário
    }

    public function NovoTipoPessoa($dados) {
        $dados['pessoa_tipo_nome'] = isset($dados['pessoa_tipo_nome']) ? trim($dados['pessoa_tipo_nome']) : '';
        $dados['pessoa_tipo_descricao'] = isset($dados['pessoa_tipo_descricao']) ? trim($dados['pessoa_tipo_descricao']) : '';
        $dados['pessoa_tipo_criado_por'] = $this->usuario_id;

        if (empty($dados['pessoa_tipo_nome']) || !isset($dados['pessoa_tipo_criado_por'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos obrigatórios.'];
        }

        return $this->tipoPessoaModel->NovoTipoPessoa($dados);
    }

    public function AtualizarTipoPessoa($id, $dados) {
        $dados['pessoa_tipo_nome'] = isset($dados['pessoa_tipo_nome']) ? trim($dados['pessoa_tipo_nome']) : '';
        $dados['pessoa_tipo_descricao'] = isset($dados['pessoa_tipo_descricao']) ? trim($dados['pessoa_tipo_descricao']) : '';

        if (empty($dados['pessoa_tipo_nome'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos obrigatórios.'];
        }

        return $this->tipoPessoaModel->AtualizarTipoPessoa($id, $dados);
    }

    public function ApagarTipoPessoa($id) {
        return $this->tipoPessoaModel->ApagarTipoPessoa($id);
    }

    public function ListarTiposPessoas() {
        return $this->tipoPessoaModel->ListarTiposPessoas();
    }

    public function BuscarTipoPessoa($coluna, $valor) {
        if (!in_array($coluna, ['pessoa_tipo_id', 'pessoa_tipo_nome'])) {
            return ['status' => 'invalid_column', 'message' => 'A coluna selecionada é inválida'];
        }

        return $this->tipoPessoaModel->BuscarTipoPessoa($coluna, $valor);
    }
}
