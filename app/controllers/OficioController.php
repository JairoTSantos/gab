<?php

require_once dirname(__DIR__) . '/models/OficioModel.php';
require_once dirname(__DIR__) . '/core/UploadFile.php';

class OficioController {

    private $oficioModel;
    private $usuario_id;
    private $uploadFile;
    private $pasta_arquivo;

    public function __construct() {
        $this->oficioModel = new OficioModel();
        $this->usuario_id = $_SESSION['usuario_id'];
        $this->uploadFile = new UploadFile();
        $this->pasta_arquivo = '/public/arquivos/oficios/';
    }

    public function NovoOficio($dados) {

        if (empty($dados['oficio_titulo']) || empty($dados['oficio_ano']) || empty($dados['arquivo'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        if (isset($dados['arquivo']['tmp_name']) && !empty($dados['arquivo']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $this->pasta_arquivo, $dados['arquivo']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['oficio_arquivo'] = $this->pasta_arquivo . $uploadResult['filename'];
            } else {
                if ($uploadResult['status'] == 'file_not_permitted') {
                    return ['status' => 'file_not_permitted', 'message' => 'Tipo de arquivo não permitido. ' . $uploadResult['permitted_files'], 'permitted_files' => $uploadResult['permitted_files']];
                }
                if ($uploadResult['status'] == 'file_too_large') {
                    return ['status' => 'file_too_large', 'message' => 'O arquivo deve ter no máximo ' . $uploadResult['maximun_size']];
                }
                if ($uploadResult['status'] == 'error') {
                    return ['status' => 'error', 'message' => 'Erro ao fazer upload.'];
                }
            }
        }

        $dados['oficio_criado_por'] = $this->usuario_id;

        $result = $this->oficioModel->NovoOficio($dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Ofício inserido com sucesso.'];
        }

        if ($result['status'] == 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Esse ofício já está inserido.'];
        }

        if ($result['status'] == 'error') {
            if (isset($dados['oficio_arquivo']) && !empty($dados['oficio_arquivo'])) {
                unlink('..' . $dados['oficio_arquivo']);
                return ['status' => 'error', 'message' => 'Erro ao inserir o ofício.'];
            }
        }
    }

    public function AtualizarOficio($dados, $id) {

        if (empty($dados['oficio_titulo']) || empty($dados['oficio_ano'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        if (isset($dados['arquivo']['tmp_name']) && !empty($dados['arquivo']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $this->pasta_arquivo, $dados['arquivo']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['oficio_arquivo'] = $this->pasta_arquivo . $uploadResult['filename'];
            } else {
                if ($uploadResult['status'] == 'file_not_permitted') {
                    return ['status' => 'file_not_permitted', 'message' => 'Tipo de arquivo não permitido. ' . $uploadResult['permitted_files'], 'permitted_files' => $uploadResult['permitted_files']];
                }
                if ($uploadResult['status'] == 'file_too_large') {
                    return ['status' => 'file_too_large', 'message' => 'O arquivo deve ter no máximo ' . $uploadResult['maximun_size']];
                }
                if ($uploadResult['status'] == 'error') {
                    return ['status' => 'error', 'message' => 'Erro ao fazer upload.'];
                }
            }
        }

        $result = $this->oficioModel->AtualizarOficio($id, $dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Ofício atualizado com sucesso.'];
        }

        if ($result['status'] == 'error') {
            if (isset($dados['oficio_arquivo']) && !empty($dados['oficio_arquivo'])) {
                unlink('..' . $dados['oficio_arquivo']);
                return ['status' => 'error', 'message' => 'Erro ao inserir o ofício.'];
            }
        }
    }

    public function ListarOficios($ano, $busca) {


        $result = $this->oficioModel->ListarOficios($ano, $busca);


        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum ofício registrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar ofícios.'];
        }
    }

    public function BuscarOfício($coluna = 'oficio_id', $valor) {

        $result = $this->oficioModel->BuscarOfício($coluna, $valor);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum usuário registrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao buscar usuário.'];
        }
    }

    public function ApagarOficio($id) {


        $result = $this->oficioModel->BuscarOfício('oficio_id', $id);

        $resultDelete = $this->oficioModel->ApagarOficio($id);

        if ($resultDelete['status'] == 'success') {
            if ($result['dados']['oficio_arquivo'] != null) {
                unlink('..' . $result['dados']['oficio_arquivo']);
            }
            return ['status' => 'success', 'message' => 'Oficio apagado com sucesso. Aguarde...'];
        }

        if ($resultDelete['status'] == 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Esse Oficio não pode ser apagado.'];
        }

        if ($resultDelete['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar ofícios.'];
        }
    }
}
