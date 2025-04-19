<?php
// Caminho seguro da chave privada (fora do public_html, se possível)
$chave_privada = file_get_contents(__DIR__ . "/chave_privada.pem");

$senha_criptografada = base64_decode($_POST['senha_segura']);
$senha = null;

if (openssl_private_decrypt($senha_criptografada, $senha, $chave_privada, OPENSSL_PKCS1_OAEP_PADDING)) {
    echo "<h2>Usuário: " . htmlspecialchars($_POST['usuario']) . "</h2>";
    echo "<h2>Senha descriptografada: " . htmlspecialchars($senha) . "</h2>";
} else {
    echo "<h2>Erro ao descriptografar a senha!</h2>";
}
?>
