<?php

require_once dirname(__DIR__) . '/models/PostagemModel.php';

class PostagemController {

    private $postagemModel;
    private $usuario_id;

    public function __construct() {
        $this->postagemModel = new PostagemModel();
        $this->usuario_id = $_SESSION['usuario_id'];
    }

    public function NovaPostagem($dados) {

        if (empty($dados['postagem_titulo']) || empty($dados['postagem_data']) || empty($dados['postagem_informacoes']) || !isset($dados['postagem_status'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }
        $dados['postagem_criada_por'] = $this->usuario_id;

        $pasta = uniqid();

        mkdir('./arquivos/postagens/'.uniqid(), 0755, true);

        $dados['postagem_pasta'] = $pasta;

        $result = $this->postagemModel->NovaPostagem($dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Postagem inserida com sucesso.'];
        }

        if ($result['status'] == 'duplicated') {
            return ['status' => 'duplicated', 'message' => 'Essa postagem já está inserida.'];
        }

        if ($result['status'] == 'error') {
            unlink('./arquivos/postagens/'.$pasta);
            return ['status' => 'error', 'message' => 'Erro ao inserir a postagem.'];
        }
    }

    public function AtualizarPostagem($id, $dados) {

        $result = $this->postagemModel->AtualizarPostagem($id, $dados);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Postagem atualizada com sucesso. Aguarde...'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao inserir a postagem.'];
        }
    }

    public function BuscarPostagem($coluna, $valor) {
        if (!in_array($coluna, ['postagem_id', 'postagem_titulo'])) {
            return ['status' => 'invalid_column', 'message' => 'A coluna selecionada é inválida'];
        }

        $result = $this->postagemModel->BuscarPostagem($coluna, $valor);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhuma postagem encontrada.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao buscar postagem.'];
        }
    }

    public function ListarPostagens($itens = 10, $pagina = 1, $ordem = 'asc', $ordenarPor = 'postagem_data') {
        $ordenarPor = in_array($ordenarPor, ['postagem_id', 'postagem_titulo', 'postagem_criada_em']) ? $ordenarPor : 'postagem_data';
        $ordem = strtoupper($ordem) === 'DESC' ? 'DESC' : 'ASC';

        $result = $this->postagemModel->ListarPostagens($itens, $pagina, $ordem, $ordenarPor);

        if ($result['status'] == 'success') {
            return ['status' => 'success', 'dados' => $result['dados']];
        }

        if ($result['status'] == 'empty') {
            return ['status' => 'empty', 'message' => 'Nenhuma postagem encontrada.'];
        }

        if ($result['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao listar postagens.'];
        }
    }

    public function ApagarPostagem($id) {
        $result = $this->postagemModel->BuscarPostagem('postagem_id', $id);

        $resultDelete = $this->postagemModel->ApagarPostagem($id);

        if ($resultDelete['status'] == 'success') {
            return ['status' => 'success', 'message' => 'Postagem apagada com sucesso. Aguarde...', 'dados' => $result['dados']];
        }

        if ($resultDelete['status'] == 'delete_conflict') {
            return ['status' => 'delete_conflict', 'message' => 'Essa postagem não pode ser apagada.'];
        }

        if ($resultDelete['status'] == 'error') {
            return ['status' => 'error', 'message' => 'Erro ao apagar postagem.'];
        }
    }
}
