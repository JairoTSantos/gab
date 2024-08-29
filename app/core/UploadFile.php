<?php

class UploadFile {
    
    public function salvarArquivo($pasta, $nomeArquivo, $arquivoTmp) {
        if (!file_exists($pasta)) {
            mkdir($pasta, 0777, true);
        }
    
        $extensao = pathinfo($arquivoTmp, PATHINFO_EXTENSION);
        if ($extensao) {
            if (!strpos($nomeArquivo, '.')) {
                $nomeArquivo .= '.' . $extensao;
            }
        }
    
        $caminhoArquivo = $pasta . DIRECTORY_SEPARATOR . $nomeArquivo;
    
        if (move_uploaded_file($arquivoTmp, $caminhoArquivo)) {
            return true;
        } else {
            return false;
        }
    }
    

    public function apagarArquivo($pasta, $nomeArquivo) {
        $caminhoArquivo = $pasta . DIRECTORY_SEPARATOR . $nomeArquivo;
        
        if (file_exists($caminhoArquivo)) {
            return unlink($caminhoArquivo);
        } else {
            return false;
        }
    }
}

?>
