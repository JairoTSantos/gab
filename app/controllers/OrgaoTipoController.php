<?php

require_once dirname(__DIR__) . '/models/OrgaoTipoModel.php';

class OrgaoTipoController {

    private $orgaoTipoModel;
    private $usuario_id;

    public function __construct() {
        $this->orgaoTipoModel = new OrgaoTipoModel();
        $this->usuario_id = 1000; // Pegado da sessão ou configurado conforme necessário
    }

    public function NovoOrgaoTipo($dados) {
        $dados['orgao_tipo_nome'] = isset($dados['orgao_tipo_nome']) ? trim($dados['orgao_tipo_nome']) : '';
        $dados['orgao_tipo_descricao'] = isset($dados['orgao_tipo_descricao']) ? trim($dados['orgao_tipo_descricao']) : '';
        $dados['orgao_tipo_criado_por'] = $this->usuario_id;

        if (empty($dados['orgao_tipo_nome']) || !isset($dados['orgao_tipo_criado_por'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos obrigatórios.'];
        }

        return $this->orgaoTipoModel->NovoOrgaoTipo($dados);
    }

    public function AtualizarOrgaoTipo($id, $dados) {
        $dados['orgao_tipo_nome'] = isset($dados['orgao_tipo_nome']) ? trim($dados['orgao_tipo_nome']) : '';
        $dados['orgao_tipo_descricao'] = isset($dados['orgao_tipo_descricao']) ? trim($dados['orgao_tipo_descricao']) : '';

        if (empty($dados['orgao_tipo_nome'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos obrigatórios.'];
        }

        return $this->orgaoTipoModel->AtualizarOrgaoTipo($id, $dados);
    }

    public function ApagarOrgaoTipo($id) {
        return $this->orgaoTipoModel->ApagarOrgaoTipo($id);
    }

    public function ListarOrgaosTipos() {
        return $this->orgaoTipoModel->ListarOrgaosTipos();
    }

    public function BuscarOrgaoTipo($coluna, $valor) {
        if (!in_array($coluna, ['orgao_tipo_id', 'orgao_tipo_nome'])) {
            return ['status' => 'invalid_column', 'message' => 'A coluna selecionada é inválida'];
        }

        return $this->orgaoTipoModel->BuscarOrgaoTipo($coluna, $valor);
    }
}
