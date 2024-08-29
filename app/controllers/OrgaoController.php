<?php

require_once dirname(__DIR__) . '/models/OrgaoModel.php';

class OrgaoController {

    private $orgaoModel;
    private $usuario_id;
    private $usuario_nivel;


    public function __construct() {;
        $this->usuario_id = $_SESSION['usuario_id'];
        $this->orgaoModel = new OrgaoModel();
        $this->usuario_nivel = isset($_SESSION['usuario_nivel']) ? $_SESSION['usuario_nivel'] : null;
    }

    public function novoOrgao($dados) {
        $dados['orgao_nome'] = isset($dados['orgao_nome']) ? htmlspecialchars(trim($dados['orgao_nome'])) : '';
        $dados['orgao_email'] = isset($dados['orgao_email']) ? htmlspecialchars(trim($dados['orgao_email'])) : '';
        $dados['orgao_telefone'] = isset($dados['orgao_telefone']) ? htmlspecialchars(trim($dados['orgao_telefone'])) : '';
        $dados['orgao_endereco'] = isset($dados['orgao_endereco']) ? htmlspecialchars(trim($dados['orgao_endereco'])) : '';
        $dados['orgao_bairro'] = isset($dados['orgao_bairro']) ? htmlspecialchars(trim($dados['orgao_bairro'])) : '';
        $dados['orgao_municipio'] = isset($dados['orgao_municipio']) ? htmlspecialchars(trim($dados['orgao_municipio'])) : '';
        $dados['orgao_estado'] = isset($dados['orgao_estado']) ? htmlspecialchars(trim($dados['orgao_estado'])) : '';
        $dados['orgao_cep'] = isset($dados['orgao_cep']) ? htmlspecialchars(trim($dados['orgao_cep'])) : '';
        $dados['orgao_tipo'] = isset($dados['orgao_tipo']) ? htmlspecialchars(trim($dados['orgao_tipo'])) : '';
        $dados['orgao_informacoes'] = isset($dados['orgao_informacoes']) ? htmlspecialchars(trim($dados['orgao_informacoes'])) : '';
        $dados['orgao_site'] = isset($dados['orgao_site']) ? htmlspecialchars(trim($dados['orgao_site'])) : '';
        $dados['orgao_criado_por'] = $this->usuario_id;


        if (empty($dados['orgao_nome']) || empty($dados['orgao_email']) || empty($dados['orgao_municipio']) || empty($dados['orgao_estado'])) {
            return ['status' => 'error', 'message' => 'Preencha todos os campos.'];
        }

        if (!filter_var($dados['orgao_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_email', 'message' => 'Formato de email inválido.'];
        }

        $resultado = $this->orgaoModel->novoOrgao($dados);

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Órgão inserido com sucesso.'];
        }

        if ($resultado['status'] === 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Esse órgão já está cadastrado.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function atualizarOrgao($id, $dados) {

        $dados['orgao_nome'] = isset($dados['orgao_nome']) ? htmlspecialchars(trim($dados['orgao_nome'])) : '';
        $dados['orgao_email'] = isset($dados['orgao_email']) ? htmlspecialchars(trim($dados['orgao_email'])) : '';
        $dados['orgao_telefone'] = isset($dados['orgao_telefone']) ? htmlspecialchars(trim($dados['orgao_telefone'])) : '';
        $dados['orgao_endereco'] = isset($dados['orgao_endereco']) ? htmlspecialchars(trim($dados['orgao_endereco'])) : '';
        $dados['orgao_bairro'] = isset($dados['orgao_bairro']) ? htmlspecialchars(trim($dados['orgao_bairro'])) : '';
        $dados['orgao_municipio'] = isset($dados['orgao_municipio']) ? htmlspecialchars(trim($dados['orgao_municipio'])) : '';
        $dados['orgao_estado'] = isset($dados['orgao_estado']) ? htmlspecialchars(trim($dados['orgao_estado'])) : '';
        $dados['orgao_cep'] = isset($dados['orgao_cep']) ? htmlspecialchars(trim($dados['orgao_cep'])) : '';
        $dados['orgao_tipo'] = isset($dados['orgao_tipo']) ? htmlspecialchars(trim($dados['orgao_tipo'])) : '';
        $dados['orgao_informacoes'] = isset($dados['orgao_informacoes']) ? htmlspecialchars(trim($dados['orgao_informacoes'])) : '';
        $dados['orgao_site'] = isset($dados['orgao_site']) ? htmlspecialchars(trim($dados['orgao_site'])) : '';

        if (empty($dados['orgao_nome']) || empty($dados['orgao_email']) || empty($dados['orgao_municipio']) || empty($dados['orgao_estado'])) {
            return ['status' => 'error', 'message' => 'Preencha todos os campos.'];
        }

        $resultado = $this->orgaoModel->atualizarOrgao($id, $dados);

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Órgão atualizado com sucesso.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function apagarOrgao($id) {

        $resultado = $this->orgaoModel->apagarOrgao($id);

        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return ['status' => 'invalid_id', 'message' => 'ID do órgão é inválido.'];
        }

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Órgão apagado com sucesso. Aguarde...'];
        }

        if ($resultado['status'] === 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Esse órgão não pode ser apagado pois ele é referenciado por outros itens.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function listarOrgaos($pagina = 1, $itens = 10, $ordernarPor = 'orgao_nome', $order = 'ASC') {
        $resultado = $this->orgaoModel->listarOrgaos($pagina, $itens, $ordernarPor, $order);
        return $resultado;
    }

    public function buscarOrgao($coluna, $valor) {
        $resultado = $this->orgaoModel->buscarOrgao($coluna, $valor);
        return $resultado;
    }



    public function novoTipo($dados) {

        ##verfiicar a necessidade dessa verificacao
        if ($this->usuario_nivel != 1) {
            return ['status' => 'error', 'message' => 'Você não tem autorização para inserir novos usuários.'];
        }

        $dados['orgao_tipo_nome'] = isset($dados['orgao_tipo_nome']) ? htmlspecialchars(trim($dados['orgao_tipo_nome'])) : '';
        $dados['orgao_tipo_descricao'] = isset($dados['orgao_tipo_descricao']) ? htmlspecialchars(trim($dados['orgao_tipo_descricao'])) : '';
        $dados['orgao_tipo_criado_por'] = $this->usuario_id;


        if (empty($dados['orgao_tipo_nome'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $resultado = $this->orgaoModel->novoTipoOrgao($dados);

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Tipo de órgão inserido com sucesso.'];
        }

        if ($resultado['status'] === 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Esse tipo já está cadastrado.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function listarTiposOrgaos() {
        $resultado = $this->orgaoModel->listarTiposOrgaos();
        return $resultado;
    }

    public function apagarTiposOrgaos($id) {

        $resultado = $this->orgaoModel->apagarTipoOrgao($id);

        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return ['status' => 'invalid_id', 'message' => 'ID do tipo é inválido.'];
        }

        if ($resultado['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Tipo de órgão apagado com sucesso.'];
        }

        if ($resultado['status'] === 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Esse tipo não pode ser apagado pois ele é referenciado por outros itens.'];
        }

        if ($resultado['status'] === 'error') {
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }
}
