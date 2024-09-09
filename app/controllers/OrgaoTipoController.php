<?php

require_once dirname(__DIR__) . '/models/OrgaoTipoModel.php';

class OrgaoTipoController {

    private $orgaoTipoModel;
    private $usuario_id;

    public function __construct() {
        $this->orgaoTipoModel = new orgaoTipoModel();
        $this->usuario_id = 1000; // pegar do session
    }

    public function NovoOrgao($dados) {

        if (empty($dados['orgao_tipo_nome'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $dados['orgao_criado_por'] = $this->usuario_id;

        $result = $this->orgaoTipoModel->NovoOrgaoTipo($dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Tipo de órgão inserido com sucesso.'];
        }

        if ($result['status'] == 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Esse tipo já está inserido.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao inserir o órgão.'];
        }
    }

    public function AtualizarTipoOrgao($id, $dados) {

        if (empty($dados['orgao_tipo_nome'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }



        $result = $this->orgaoTipoModel->AtualizarOrgaoTipo($id, $dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Tipo de órgão atualizado com sucesso. Aguarde...'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao inserir o orgão.'];
        }
    }


    public function ListarTipoOrgaos() {
      

        $result = $this->orgaoTipoModel->ListarOrgaosTipos();


        if ($result['status'] == 'success') {
            return $result;
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum tipo registrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar tipos.'];
        }
    }


    public function BuscarTipoOrgaos($coluna, $valor) {

      

        $result = $this->orgaoTipoModel->BuscarOrgaoTipo($coluna, $valor);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum tipo registrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao buscar órgão.'];
        }
    }


    public function ApagarOrgao($id) {

      
        $resultDelete = $this->orgaoTipoModel->ApagarOrgaoTipo($id);

        if ($resultDelete['status'] == 'success') {
            
            return ['status' => 'success', 'message' => 'Tipo de órgão apagado com sucesso. Aguarde...'];
        }

        if ($resultDelete['status'] == 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Esse tipo não pode ser apagado.'];
        }

        if ($resultDelete['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar tipos.'];
        }
    }
}
