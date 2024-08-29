<?php

function getJson($url) {
    // Inicializa a sessão cURL
    $ch = curl_init();

    // Configura a URL e outras opções
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Executa a requisição e armazena o resultado
    $response = curl_exec($ch);

    // Verifica se houve erro na requisição
    if ($response === false) {
        curl_close($ch);
        return ['error' => curl_error($ch)];
    }

    // Fecha a sessão cURL
    curl_close($ch);

    // Decodifica o JSON
    $data = json_decode($response, true);

    // Verifica se a decodificação foi bem-sucedida
    if (json_last_error() === JSON_ERROR_NONE) {
        return $data;
    } else {
        return ['error' => json_last_error_msg()];
    }
}
