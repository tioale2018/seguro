<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login Seguro</title>
</head>
<body>
  <form action="processa.php" method="POST" onsubmit="return encryptFormData(event)">
    <input type="text" name="usuario" placeholder="Usuário" required>
    <input type="password" name="senha" id="senha" placeholder="Senha" required>
    <input type="hidden" name="senha_segura" id="senha_segura">
    <button type="submit">Entrar</button>
  </form>

  <script>
    async function encryptFormData(event) {
      event.preventDefault();

      if (!window.crypto || !window.crypto.subtle) {
        alert("Este navegador não suporta criptografia com Web Crypto API.");
        return false;
      }

      const senha = document.getElementById('senha').value;
      const encoder = new TextEncoder();
      const data = encoder.encode(senha);

      const pem = `-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnaBvmUcUSg+hkQoxfRRJ
QPL6NWh+CxxOSLvE6kw04AnD+LcxkfW5gBeE1jzSSE1vRHydEbI+sf62oxiPZDKO
jry+2JP7CbXmpBI3Sfof0KLikIZctAz8FuHDmbyS80FF0oFDBduAB6OAmM0ZbMBu
4Iz0yme767CH/gO2lzlV8Wx9ZDEG8a3lSKFAwZWp4YohW7RMI1cTl6e8beShPS56
aeXld89c47cB0Spn5th+YI9+GIuRZvYm3ChKLQd0zHJL2Br8qBzxNnIDVcAr06Zp
YSC2Bw7swmiQTqBj1HgAFewN1TV3PyoynZCE24RUu0P+aNU8oZb7KnyyaiqEK9dn
EQIDAQAB
-----END PUBLIC KEY-----`;

      const pemHeader = "-----BEGIN PUBLIC KEY-----";
      const pemFooter = "-----END PUBLIC KEY-----";
      const pemContents = pem.replace(pemHeader, "").replace(pemFooter, "").replace(/\s/g, "");
      const binaryDer = Uint8Array.from(atob(pemContents), c => c.charCodeAt(0));

      const key = await crypto.subtle.importKey(
        "spki",
        binaryDer.buffer,
        { name: "RSA-OAEP", hash: "SHA-256" },
        false,
        ["encrypt"]
      );

      const encrypted = await crypto.subtle.encrypt(
        { name: "RSA-OAEP" },
        key,
        data
      );

      const encryptedBase64 = btoa(String.fromCharCode(...new Uint8Array(encrypted)));

      document.getElementById("senha_segura").value = encryptedBase64;
      document.getElementById("senha").disabled = true;

      event.target.submit();
    }
  </script>
</body>
</html>
