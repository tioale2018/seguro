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
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApWcSnPrAXkqfpW+ZcYWD
tG5TxV8F5hP6S4HpO9jGgnX8TkPbqOR3iYK2Ba9Ah1cElV9MtwY6qW5ZPcv9E51W
Uqv0biQ8v1IlNlUJ8KR93eQAk7cVXsF+i1ETGM9s8TJrAGCjLZ0MwlAfQBS7SkoK
D7Cqf8pIj6wMe+brjcj+5f3Ql4IOmcALnPfNmCZVja1kHZwJBOe6tbiTbz5UeDsZ
rSkmtVUnDniJ4epDvnZ/fCG6Zg6/CL+6ClAf7/yrpTifAe9dHn9U8mpNyaX8f1bm
VxFlLymZK88L7CBbm4gWTXYs6iQj6eW51UJMT8DLQwOq04Fx/WJPL8bVY7cD9T7g
TwIDAQAB
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
