<?php


class Login {

    public function Logar($dados) {
        require_once dirname(__DIR__) . '/core/Database.php';
        require_once dirname(__DIR__) . '/controllers/UsuarioController.php';
        $usuario = new UsuarioController;

        require_once dirname(__DIR__) . '/core/Logger.php';
        $logger = new Logger();
       

        $config = require dirname(__DIR__) . '/config/config.php';
        $userConfig = $config['master_user'];


        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_email', 'message' => 'Formato de email inválido.'];
        }
        
        if (!isset($dados['email']) || !isset($dados['senha']) || empty($dados['email']) || empty($dados['senha'])) {
            return ['status' => 'bad_request', 'message' => 'Preencha todos os campos.'];
        }

        $email =  $dados['email'];
        $senha =  $dados['senha'];



        if ($userConfig['email'] === $email && $userConfig['pass'] === $senha) {
            session_set_cookie_params([
                'lifetime' => 1 * 60 * 60,
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            session_start();
            $_SESSION['usuario_nome'] =  $userConfig['name'];
            $_SESSION['usuario_nivel'] = 1;
            $_SESSION['usuario_id'] = 1000;
            $_SESSION['usuario_token'] = uniqid();

            $logger->novoLog('log_access', $userConfig['name'] . ' - ' . $userConfig['email']);

            return ['status' => 'success', 'message' => 'Login feito com sucesso.'];
        }


        $busca = $usuario->buscarUsuario('usuario_email', $email);

        if ($busca['status'] === 'empty') {
            return ['status' => 'not_found', 'message' => 'Usuário não cadastrado.'];
        }

        if ($busca['status'] === 'success') {
            if (!$busca['dados']['usuario_ativo']) {
                return ['status' => 'deactivated', 'message' => 'Usuário desativado.'];
            }

            if (!password_verify($senha, $busca['dados']['usuario_senha'])) {
                return ['status' => 'wrong_password', 'message' => 'Senha incorreta.'];
            }

            session_set_cookie_params([
                'lifetime' => 24 * 60 * 60,
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            session_start();
            $_SESSION['usuario_nome'] = $busca['dados']['usuario_nome'];
            $_SESSION['usuario_nivel'] = $busca['dados']['usuario_nivel'];
            $_SESSION['usuario_id'] = $busca['dados']['usuario_id'];
            $_SESSION['usuario_token'] = uniqid();
            $logger->novoLog('log_access', $busca['dados']['usuario_nome'] . ' - ' . $busca['dados']['usuario_email']);
            return ['status' => 'success', 'message' => 'Login feito com sucesso.'];
        }
    }
}
