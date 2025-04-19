<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Olá, Mundo!</h1>
    <?php
    // Verifica se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Captura os dados do formulário
        // $nome = htmlspecialchars($_POST['nome']);
        // $email = htmlspecialchars($_POST['email']);
        $nome = $_POST['nome'];
        $email = $_POST['email'];

        // Exibe os dados capturados
        echo "<p>Nome: $nome</p>";
        echo "<p>E-mail: $email</p>";
    } else {
        // echo "<p>Nenhum dado enviado.</p>";
        // Redireciona para o formulário se não houver dados
        //mostra um alerta e redireciona para o index.php
        echo "<script>alert('Nenhum dado enviado.');</script>";
        header("Location: index.php");
        exit();
    }

    ?>

    <form action="atualiza.php" method="post">
        <input type="hidden" name="nome" value="<?= $nome; ?>">
        <input type="hidden" name="email" value="<?= $email; ?>">
        <input type="submit" value="Atualizar">
    </form>
    
</body>
</html>