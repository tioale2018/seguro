<?php
date_default_timezone_set('America/Sao_Paulo');
$nonce = bin2hex(random_bytes(16)); // Gera um nonce aleatório

// header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-$nonce'; object-src 'none'; base-uri 'self'");
// header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-$nonce'; style-src 'self' 'unsafe-inline'; object-src 'none'; base-uri 'self'");


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login Seguro</title>
</head>
<body>
<form action="processa.php" method="POST">
  <input type="text" name="usuario" placeholder="Usuário" required>
  <input type="password" name="senha" placeholder="Senha" required>
  <button type="submit">Entrar</button>
</form>

  <script nonce="<?= $nonce; ?>" src="./js/seguranca.js" defer></script>
</body>
</html>
