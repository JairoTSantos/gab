<?php

require_once dirname(__DIR__) . '/models/OrgaoModel.php';

class OrgaoController {

    private $orgaoModel;
    private $usuario_id;

    public function __construct() {
        $this->orgaoModel = new OrgaoModel();
        $this->usuario_id = 1000; // pegar do session
    }

    public function NovoOrgao($dados) {

        if (empty($dados['orgao_nome']) || empty($dados['orgao_email']) || empty($dados['orgao_municipio']) || empty($dados['orgao_estado']) || empty($dados['orgao_tipo'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $dados['orgao_criado_por'] = $this->usuario_id;

        $result = $this->orgaoModel->NovoOrgao($dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Órgão inserido com sucesso.'];
        }

        if ($result['status'] == 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Esse órgão já está inserido.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao inserir o órgão.'];
        }
    }

    public function AtualizarOrgao($id, $dados) {

        if (empty($dados['orgao_nome']) || empty($dados['orgao_email']) || empty($dados['orgao_municipio'])  || empty($dados['orgao_estado'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }



        $result = $this->orgaoModel->AtualizarOrgao($id, $dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Órgão atualizado com sucesso. Aguarde...'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao inserir o orgão.'];
        }
    }


    public function ListarOrgaos($itens = 10, $pagina = 1, $ordem = 'asc', $ordenarPor = 'orgao_nome', $termo = '', $filtro = false) {
        $ordenarPor = in_array($ordenarPor, ['orgao_id', 'orgao_nome', 'orgao_criado_em']) ? $ordenarPor : 'orgao_nome';
        $ordem = strtoupper($ordem) === 'DESC' ? 'DESC' : 'ASC';

        $result = $this->orgaoModel->ListarOrgaos($itens, $pagina, $ordem, $ordenarPor, $termo, $filtro);


        if ($result['status'] == 'success') {
            return $result;
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum órgão registrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar órgãos.'];
        }
    }


    public function BuscarOrgaos($coluna, $valor) {

        if (!in_array($coluna, ['orgao_id', 'orgao_email'])) {
            return ['status' => 'invalid_column', 'message' => 'A coluna selecionada é inválida'];
        }

        $result = $this->orgaoModel->BuscarOrgao($coluna, $valor);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum órgão registrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao buscar órgão.'];
        }
    }


    public function ApagarOrgao($id) {

      
        $resultDelete = $this->orgaoModel->ApagarOrgao($id);

        if ($resultDelete['status'] == 'success') {
            
            return ['status' => 'success', 'message' => 'Órgão apagado com sucesso. Aguarde...'];
        }

        if ($resultDelete['status'] == 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Esse Órgão não pode ser apagado.'];
        }

        if ($resultDelete['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar usuários.'];
        }
    }
}
