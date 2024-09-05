<?php

require_once dirname(__DIR__) . '/models/UsuarioModel.php';
require_once dirname(__DIR__) . '/core/UploadFile.php';

class UsuarioController {

    private $usuarioModel;
    private $uploadFile;
    private $pasta_foto;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
        $this->uploadFile = new UploadFile();
        $this->pasta_foto = '/public/arquivos/fotos_usuarios/';
    }


    public function NovoUsuario($dados) {

        $dados['usuario_nome'] = isset($dados['usuario_nome']) ? trim($dados['usuario_nome']) : '';
        $dados['usuario_email'] = isset($dados['usuario_email']) ? trim($dados['usuario_email']) : '';
        $dados['usuario_telefone'] = isset($dados['usuario_telefone']) ? trim($dados['usuario_telefone']) : '';
        $dados['usuario_senha'] = isset($dados['usuario_senha']) ? trim($dados['usuario_senha']) : '';
        $dados['usuario_nivel'] = isset($dados['usuario_nivel']) ? (int) $dados['usuario_nivel'] : 2;
        $dados['usuario_ativo'] = isset($dados['usuario_ativo']) ? (int) $dados['usuario_ativo'] : 0;
        $dados['usuario_aniversario'] = isset($dados['usuario_aniversario']) ? trim($dados['usuario_aniversario']) : '';

        if (!filter_var($dados['usuario_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_email', 'message' => 'Formato de email inválido.'];
        }

        if (empty($dados['usuario_nome']) || empty($dados['usuario_email']) || empty($dados['usuario_telefone']) || empty($dados['usuario_senha']) || !isset($dados['usuario_nivel']) || !isset($dados['usuario_ativo']) || empty($dados['usuario_aniversario'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        if (isset($dados['foto']['tmp_name']) && !empty($dados['foto']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $this->pasta_foto, $dados['foto']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['usuario_foto'] = $this->pasta_foto . $uploadResult['filename'];
            } else {
                return $uploadResult;
            }
        }

        $result =  $this->usuarioModel->NovoUsuario($dados);

        if ($result['status'] == 'error' || $result['status'] == 'duplicated') {
            if (isset($dados['usuario_foto']) && !empty($dados['usuario_foto'])) {
                unlink('..' . $dados['usuario_foto']);
            }
        }

        return $result;
    }

    public function AtualizarUsuario($id, $dados) {

        $dados['usuario_nome'] = isset($dados['usuario_nome']) ? trim($dados['usuario_nome']) : '';
        $dados['usuario_email'] = isset($dados['usuario_email']) ? trim($dados['usuario_email']) : '';
        $dados['usuario_telefone'] = isset($dados['usuario_telefone']) ? trim($dados['usuario_telefone']) : '';
        $dados['usuario_senha'] = isset($dados['usuario_senha']) ? trim($dados['usuario_senha']) : '';
        $dados['usuario_nivel'] = isset($dados['usuario_nivel']) ? (int) $dados['usuario_nivel'] : 2;
        $dados['usuario_ativo'] = isset($dados['usuario_ativo']) ? (int) $dados['usuario_ativo'] : 0;
        $dados['usuario_aniversario'] = isset($dados['usuario_aniversario']) ? trim($dados['usuario_aniversario']) : '';

        if (!filter_var($dados['usuario_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_email', 'message' => 'Formato de email inválido.'];
        }

        if (empty($dados['usuario_nome']) || empty($dados['usuario_email']) || empty($dados['usuario_telefone']) || empty($dados['usuario_senha']) || !isset($dados['usuario_nivel']) || !isset($dados['usuario_ativo']) || empty($dados['usuario_aniversario'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        if (isset($dados['foto']['tmp_name']) && !empty($dados['foto']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $this->pasta_foto, $dados['foto']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['usuario_foto'] = $this->pasta_foto . $uploadResult['filename'];
            } else {
                return $uploadResult;
            }
        } else {
            $dados['usuario_foto'] = '';
        }

        $result = $this->usuarioModel->AtualizarUsuario($id, $dados);

        if ($result['status'] == 'error') {
            if (isset($dados['usuario_foto']) && !empty($dados['usuario_foto'])) {
                unlink('..' . $dados['usuario_foto']);
            }
        }

        return $result;
    }

    public function BuscarUsuario($coluna, $valor) {

        if (!in_array($coluna, ['usuario_id', 'usuario_email'])) {
            return ['status' => 'invalid_column', 'message' => 'A coluna selecionada é inválida'];
        }

        return $this->usuarioModel->buscarUsuario($coluna, $valor);
    }

    function ApagarUsuario($id) {
        $result = $this->buscarUsuario('usuario_id', $id);

        if ($result['status'] == 'success' && $result['dados']['usuario_foto'] != null) {
            unlink('..' . $result['dados']['usuario_foto']);
        }

        return $this->usuarioModel->ApagarUsuario($id);
    }
}
