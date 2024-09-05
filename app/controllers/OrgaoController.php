<?php

require_once dirname(__DIR__) . '/models/OrgaoModel.php';

class OrgaoController {

    private $orgaoModel;
    private $usuario_id;

    public function __construct() {
        $this->orgaoModel = new OrgaoModel();
        $this->usuario_id = 1000; //pegaro do session
    }

    public function BuscarOrgao($coluna, $valor) {

        if (!in_array($coluna, ['orgao_id', 'orgao_email', 'orgao_estado', 'orgao_municipio'])) {
            return ['status' => 'invalid_column', 'message' => 'A coluna selecionada é inválida'];
        }

        return $this->orgaoModel->BuscarOrgao($coluna, $valor);
    }

    public function ListarOrgaos($itens = 10, $pagina = 1, $ordem = 'asc', $ordenarPor = 'usuario_nome') {
        $ordernarPor = in_array($ordenarPor, ['usuario_nome', 'usuario_criado_por']) ? $ordenarPor : 'usuario_nome';
        $order = strtoupper($ordem) === 'DESC' ? 'DESC' : 'ASC';
        return $this->orgaoModel->ListarOrgaos($itens, $pagina, $ordem, $ordenarPor);
    }

    public function ApagarOrgao($id) {
        return $this->orgaoModel->ApagarOrgao($id);
    }

    public function NovoOrgao($dados) {

        $dados['orgao_nome'] = isset($dados['orgao_nome']) ? trim($dados['orgao_nome']) : '';
        $dados['orgao_email'] = isset($dados['orgao_email']) ? trim($dados['orgao_email']) : '';
        $dados['orgao_telefone'] = isset($dados['orgao_telefone']) ? trim($dados['orgao_telefone']) : '';
        $dados['orgao_endereco'] = isset($dados['orgao_endereco']) ? trim($dados['orgao_endereco']) : '';
        $dados['orgao_bairro'] = isset($dados['orgao_bairro']) ? trim($dados['orgao_bairro']) : '';
        $dados['orgao_municipio'] = isset($dados['orgao_municipio']) ? trim($dados['orgao_municipio']) : '';
        $dados['orgao_estado'] = isset($dados['orgao_estado']) ? trim($dados['orgao_estado']) : '';
        $dados['orgao_cep'] = isset($dados['orgao_cep']) ? trim($dados['orgao_cep']) : '';
        $dados['orgao_tipo'] = isset($dados['orgao_tipo']) ? (int) $dados['orgao_tipo'] : 1;
        $dados['orgao_informacoes'] = isset($dados['orgao_informacoes']) ? trim($dados['orgao_informacoes']) : '';
        $dados['orgao_site'] = isset($dados['orgao_site']) ? trim($dados['orgao_site']) : '';
        $dados['orgao_criado_por'] = $this->usuario_id;

        if (!filter_var($dados['orgao_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_email', 'message' => 'Formato de email inválido.'];
        }

        if (empty($dados['orgao_nome']) || empty($dados['orgao_email']) || empty($dados['orgao_municipio']) || empty($dados['orgao_estado']) || !isset($dados['orgao_tipo'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos obrigatórios.'];
        }

        return $this->orgaoModel->NovoOrgao($dados);
    }

    public function AtualizarOrgao($id, $dados) {

        $dados['orgao_nome'] = isset($dados['orgao_nome']) ? trim($dados['orgao_nome']) : '';
        $dados['orgao_email'] = isset($dados['orgao_email']) ? trim($dados['orgao_email']) : '';
        $dados['orgao_telefone'] = isset($dados['orgao_telefone']) ? trim($dados['orgao_telefone']) : '';
        $dados['orgao_endereco'] = isset($dados['orgao_endereco']) ? trim($dados['orgao_endereco']) : '';
        $dados['orgao_bairro'] = isset($dados['orgao_bairro']) ? trim($dados['orgao_bairro']) : '';
        $dados['orgao_municipio'] = isset($dados['orgao_municipio']) ? trim($dados['orgao_municipio']) : '';
        $dados['orgao_estado'] = isset($dados['orgao_estado']) ? trim($dados['orgao_estado']) : '';
        $dados['orgao_cep'] = isset($dados['orgao_cep']) ? trim($dados['orgao_cep']) : '';
        $dados['orgao_tipo'] = isset($dados['orgao_tipo']) ? (int) $dados['orgao_tipo'] : 1;
        $dados['orgao_informacoes'] = isset($dados['orgao_informacoes']) ? trim($dados['orgao_informacoes']) : '';
        $dados['orgao_site'] = isset($dados['orgao_site']) ? trim($dados['orgao_site']) : '';

        if (!filter_var($dados['orgao_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_email', 'message' => 'Formato de email inválido.'];
        }

        if (empty($dados['orgao_nome']) || empty($dados['orgao_email']) || empty($dados['orgao_municipio']) || empty($dados['orgao_estado']) || !isset($dados['orgao_tipo'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos obrigatórios.'];
        }

        return $this->orgaoModel->AtualizarOrgao($id, $dados);
    }
}
