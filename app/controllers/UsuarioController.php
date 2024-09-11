<?php

require_once dirname(__DIR__) . '/models/UsuarioModel.php';
require_once dirname(__DIR__) . '/core/UploadFile.php';

class UsuarioController {

    private $usuarioModel;
    private $uploadFile;
    private $pasta_foto;
    private $usuario_nivel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
        $this->uploadFile = new UploadFile();
        $this->pasta_foto = '/public/arquivos/fotos_usuarios/';
        $this->usuario_nivel = $_SESSION['usuario_nivel']; /// pegar do session
    }


    public function NovoUsuario($dados) {

        if ($this->usuario_nivel != 1) {
            return ['status' => 'forbidden', 'message' => 'Você não tem autorização para inserir novos usuários.'];
        }

        if (empty($dados['usuario_nome']) || empty($dados['usuario_email']) || empty($dados['usuario_telefone']) || empty($dados['usuario_senha']) || !isset($dados['usuario_nivel']) || !isset($dados['usuario_ativo']) || empty($dados['usuario_aniversario'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        if (isset($dados['foto']['tmp_name']) && !empty($dados['foto']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $this->pasta_foto, $dados['foto']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['usuario_foto'] = $this->pasta_foto . $uploadResult['filename'];
            } else {
                if ($uploadResult['status'] == 'file_not_permitted') {
                    return ['status' => 'file_not_permitted', 'message' => 'Tipo de arquivo não permitido', 'permitted_files' => $uploadResult['permitted_files']];
                }
                if ($uploadResult['status'] == 'file_too_large') {
                    return ['status' => 'file_too_large', 'message' => 'O arquivo deve ter no máximo ' . $uploadResult['maximun_size']];
                }
                if ($uploadResult['status'] == 'error') {
                    return ['status' => 'error', 'message' => 'Erro ao fazer upload.'];
                }
            }
        }

        $result = $this->usuarioModel->NovoUsuario($dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Usuário inserido com sucesso.'];
        }

        if ($result['status'] == 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Esse usuário já está inserido.'];
        }

        if ($result['status'] == 'error') {
            if (isset($dados['usuario_foto']) && !empty($dados['usuario_foto'])) {
                unlink('..' . $dados['usuario_foto']);
                return ['status' => 'error', 'message' => 'Erro ao inserir o usuário.'];
            }
        }
    }

    public function AtualizarUsuario($id, $dados) {

        if ($this->usuario_nivel != 1) {
            return ['status' => 'forbidden', 'message' => 'Você não tem autorização para atualizar usuários.'];
        }

        if (empty($dados['usuario_nome']) || empty($dados['usuario_email']) || empty($dados['usuario_telefone']) || !isset($dados['usuario_nivel']) || !isset($dados['usuario_ativo']) || empty($dados['usuario_aniversario'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        if (isset($dados['foto']['tmp_name']) && !empty($dados['foto']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $this->pasta_foto, $dados['foto']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['usuario_foto'] = $this->pasta_foto . $uploadResult['filename'];
            } else {
                if ($uploadResult['status'] == 'file_not_permitted') {
                    return ['status' => 'file_not_permitted', 'message' => 'Tipo de arquivo não permitido', 'permitted_files' => $uploadResult['permitted_files']];
                }
                if ($uploadResult['status'] == 'file_too_large') {
                    return ['status' => 'file_too_large', 'message' => 'O arquivo deve ter no máximo ' . $uploadResult['maximun_size']];
                }
                if ($uploadResult['status'] == 'error') {
                    return ['status' => 'error', 'message' => 'Erro ao fazer upload.'];
                }
            }
        } else {
            $dados['usuario_foto'] = null;
        }

        $result = $this->usuarioModel->AtualizarUsuario($id, $dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Usuário atualizado com sucesso. Aguarde...'];
        }

        if ($result['status'] == 'error') {
            if (isset($dados['usuario_foto']) && !empty($dados['usuario_foto'])) {
                unlink('..' . $dados['usuario_foto']);
                return ['status' => 'error', 'message' => 'Erro ao inserir o usuário.'];
            }
        }
    }

    public function BuscarUsuario($coluna, $valor) {

        if (!in_array($coluna, ['usuario_id', 'usuario_email'])) {
            return ['status' => 'invalid_column', 'message' => 'A coluna selecionada é inválida'];
        }

        $result = $this->usuarioModel->BuscarUsuario($coluna, $valor);

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

    public function ListarUsuarios($itens = 1000, $pagina = 1, $ordem = 'asc', $ordenarPor = 'pessoa_nome') {
        $ordenarPor = in_array($ordenarPor, ['usuario_id', 'usuario_nome', 'usuario_criado_em']) ? $ordenarPor : 'usuario_nome';
        $ordem = strtoupper($ordem) === 'DESC' ? 'DESC' : 'ASC';

        $result = $this->usuarioModel->ListarUsuarios($itens, $pagina, $ordem, $ordenarPor);


        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum usuário registrado.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar usuários.'];
        }
    }

    public function ApagarUsuario($id) {

        $result = $this->usuarioModel->BuscarUsuario('usuario_id', $id);

        $resultDelete = $this->usuarioModel->ApagarUsuario($id);

        if ($resultDelete['status'] == 'success') {
            if ($result['dados']['usuario_foto'] != null) {
                unlink('..' . $result['dados']['usuario_foto']);
            }
            return ['status' => 'success', 'message' => 'Usuário apagado com sucesso. Aguarde...', 'dados' => $result['dados']];
        }

        if ($resultDelete['status'] == 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Esse usuário não pode ser apagado.'];
        }

        if ($resultDelete['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar usuários.'];
        }
    }
}
