<?php

class UploadFile {

    private $appConfig;

    public function __construct() {
        $this->appConfig = require dirname(__DIR__) . '/config/config.php';
    }

    public function salvarArquivo($pasta, $arquivo) {
        if (!file_exists($pasta)) {
            mkdir($pasta, 0755, true);
        }

        $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);

        $arquivosPermitidos = $this->appConfig['app']['permitted_files'];
        $tiposPermitidos = implode(', ', $arquivosPermitidos);

        if (!in_array($extensao, $arquivosPermitidos)) {
            return [
                'status' => 'file_not_permitted',
                'permitted_files' => $tiposPermitidos
            ];
        }

        if ($arquivo['size'] > $this->appConfig['app']['maximum_file_size'] * 1024 * 1024) {
            return [
                'status' => 'file_too_large',
                'maximun_size' => $this->appConfig['app']['maximum_file_size'] . ' MB.'
            ];
        }

        $nomeArquivo = uniqid() . '.' . $extensao;
        $caminhoArquivo = $pasta . DIRECTORY_SEPARATOR . $nomeArquivo;

        if (move_uploaded_file($arquivo['tmp_name'], $caminhoArquivo)) {
            return ['status' => 'upload_ok', 'filename' => $nomeArquivo];
        } else {
            return ['status' => 'error'];
        }
    }
}
