<?php
require_once 'phpseclib/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

// Carrega a chave privada do arquivo
$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');

// Decodifica a senha criptografada
$encrypted = base64_decode($_POST['senha_segura'] ?? '');

try {
    $decrypted = $privateKey->decrypt($encrypted);
    echo "<h2>Usuário: " . htmlspecialchars($_POST['usuario']) . "</h2>";
    echo "<h2>Senha descriptografada: " . htmlspecialchars($decrypted) . "</h2>";
} catch (Exception $e) {
    echo "Erro ao descriptografar: " . $e->getMessage();
}

echo "<hr>";
echo "<h2>Dados recebidos:</h2>";
echo $_POST['usuario'] . "<br>";
echo $_POST['senha_segura'] . "<br>";
// echo $_POST['senha'] . "<br>";
echo "<hr>";

if (!openssl_private_decrypt($senha_criptografada, $senha, $chave_privada, OPENSSL_PKCS1_OAEP_PADDING)) {
    echo "Erro ao descriptografar a senha!<br>";
    echo "<pre>" . openssl_error_string() . "</pre>";
    exit;
}

?>



