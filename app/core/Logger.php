<?php

class Logger {
    function novoLog($title, $message) {
        $logFile = dirname(__DIR__, 2) . '/logs/' . date('Y_m_d') . '_' . $title . '.log';
        //$formattedMessage = date('Y-m-d H:i:s'). ' - Usuário logado: ' .$_SESSION['usuario_nome']. " - " . $message . PHP_EOL;
        $formattedMessage = date('Y-m-d H:i:s') . $message . PHP_EOL;
        file_put_contents($logFile, $formattedMessage, FILE_APPEND | LOCK_EX);
    }
}
