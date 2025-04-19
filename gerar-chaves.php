<?php
$config = [
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
    "private_key_bits" => 2048,
];

$res = openssl_pkey_new($config);

// Exporta a chave privada no formato PKCS#8 (BEGIN PRIVATE KEY)
openssl_pkey_export($res, $chave_privada);

// Extrai a chave pública
$detalhes = openssl_pkey_get_details($res);
$chave_publica = $detalhes["key"];

// Salva os arquivos
file_put_contents("chave_privada.pem", $chave_privada);
file_put_contents("chave_publica.pem", $chave_publica);

echo "Par de chaves RSA gerado com sucesso.";
echo "<br><br>";
?>