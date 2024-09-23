<?php

require_once dirname(__DIR__) . '/models/ClippingModel.php';
require_once dirname(__DIR__) . '/core/UploadFile.php';

class ClippingController {

    private $clippingModel;
    private $usuario_id;
    private $uploadFile;
    private $pasta_arquivo;

    public function __construct() {
        $this->clippingModel = new ClippingModel();
        $this->pasta_arquivo = '/public/arquivos/clippings/';
        $this->uploadFile = new UploadFile();
        $this->usuario_id = $_SESSION['usuario_id'];
    }

    public function NovoClipping($dados) {

        if (empty($dados['clipping_resumo']) || empty($dados['clipping_link']) || empty($dados['clipping_orgao']) || empty($dados['clipping_tipo'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos obrigatórios.'];
        }

        $dados['clipping_criado_por'] = $this->usuario_id;

        if (isset($dados['arquivo']['tmp_name']) && !empty($dados['arquivo']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $this->pasta_arquivo, $dados['arquivo']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['clipping_arquivo'] = $this->pasta_arquivo . $uploadResult['filename'];
            } else {
                if ($uploadResult['status'] == 'file_not_permitted') {
                    return ['status' => 'file_not_permitted', 'message' => 'Tipo de arquivo não permitido', 'permitted_files' => $uploadResult['permitted_files']];
                }
                if ($uploadResult['status'] == 'file_too_large') {
                    return ['status' => 'file_too_large', 'message' => 'O arquivo deve ter no máximo ' . $uploadResult['maximun_size']];
                }
                if ($uploadResult['status'] == 'error') {
                    return ['status' => 'error', 'message' => 'Erro ao fazer upload do arquivo.'];
                }
            }
        }

        $result = $this->clippingModel->NovoClipping($dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Clipping inserido com sucesso.'];
        }

        if ($result['status'] == 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Esse clipping já está registrado.'];
        }

        return ['status' => 'error', 'message' => 'Erro ao inserir o clipping.'];
    }

    public function AtualizarClipping($id, $dados) {

        if (empty($dados['clipping_resumo']) || empty($dados['clipping_link']) || empty($dados['clipping_orgao']) || empty($dados['clipping_tipo'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos obrigatórios.'];
        }

        if (isset($dados['arquivo']['tmp_name']) && !empty($dados['arquivo']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo('..' . $this->pasta_arquivo, $dados['arquivo']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['clipping_arquivo'] = $this->pasta_arquivo . $uploadResult['filename'];
            } else {
                if ($uploadResult['status'] == 'file_not_permitted') {
                    return ['status' => 'file_not_permitted', 'message' => 'Tipo de arquivo não permitido', 'permitted_files' => $uploadResult['permitted_files']];
                }
                if ($uploadResult['status'] == 'file_too_large') {
                    return ['status' => 'file_too_large', 'message' => 'O arquivo deve ter no máximo ' . $uploadResult['maximun_size']];
                }
                return ['status' => 'error', 'message' => 'Erro ao fazer upload do arquivo.'];
            }
        }

        $result = $this->clippingModel->AtualizarClipping($id, $dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Clipping atualizado com sucesso.'];
        }

        return ['status' => 'error', 'message' => 'Erro ao atualizar o clipping.'];
    }

    public function ListarClippings($itens = 10, $pagina = 1, $ordem = 'asc', $ordenarPor = 'clipping_resumo', $termo = '') {
        $ordenarPor = in_array($ordenarPor, ['clipping_id', 'clipping_resumo', 'clipping_orgao', 'clipping_tipo']) ? $ordenarPor : 'clipping_resumo';
        $ordem = strtoupper($ordem) === 'DESC' ? 'DESC' : 'ASC';

        $result = $this->clippingModel->ListarClippings($itens, $pagina, $ordem, $ordenarPor, $termo);

        if ($result['status'] == 'success') {
            return $result;
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum clipping encontrado.'];
        }

        return ['status' => 'error', 'message' => 'Erro ao listar clippings.'];
    }

    public function BuscarClipping($coluna, $valor) {

        if (!in_array($coluna, ['clipping_id', 'clipping_link'])) {
            return ['status' => 'invalid_column', 'message' => 'A coluna selecionada é inválida'];
        }

        $result = $this->clippingModel->BuscarClipping($coluna, $valor);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhum clipping encontrado.'];
        }

        return ['status' => 'error', 'message' => 'Erro ao buscar clipping.'];
    }

    public function ApagarClipping($id) {

        $result = $this->clippingModel->BuscarClipping('clipping_id', $id);

        if ($result['status'] != 'success') {
            return ['status' => 'error', 'message' => 'Erro ao buscar clipping.'];
        }

        $resultDelete = $this->clippingModel->ApagarClipping($id);

        if ($resultDelete['status'] == 'success') {
            if ($result['dados']['clipping_arquivo'] != null) {
                unlink('..' . $result['dados']['clipping_arquivo']);
            }
            return ['status' => 'success', 'message' => 'Clipping apagado com sucesso.'];
        }

        if ($resultDelete['status'] == 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Esse clipping não pode ser apagado.'];
        }

        return ['status' => 'error', 'message' => 'Erro ao apagar o clipping.'];
    }
}
