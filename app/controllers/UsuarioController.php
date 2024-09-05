<?php

require_once dirname(__DIR__) . '/models/UsuarioModel.php';
require_once dirname(__DIR__) . '/core/UploadFile.php';

class UsuarioController {

    private $usuarioModel;
    private $uploadFile;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
        $this->uploadFile = new UploadFile();
    }


    public function criarUsuario($dados) {

        $pasta_fotos = '/public/arquivos/fotos_usuarios/';

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
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $pasta_fotos, $dados['foto']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['usuario_foto'] = $pasta_fotos . $uploadResult['filename'];
            } else {
                return $uploadResult;
            }
        }

        return $this->usuarioModel->NovoUsuario($dados);
    }

    public function atualizarUsuario($id, $dados) {

        $pasta_fotos = '/public/arquivos/fotos_usuarios/';

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
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $pasta_fotos, $dados['foto']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['usuario_foto'] = $pasta_fotos . $uploadResult['filename'];
            } else {
                return $uploadResult;
            }
        } else {
            $dados['usuario_foto'] = '';
        }

        return $this->usuarioModel->AtualizarUsuario($id, $dados);
    }
}
