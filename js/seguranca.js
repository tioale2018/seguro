async function encryptFormData(event) {
    event.preventDefault();

    if (!window.crypto || !window.crypto.subtle) {
      alert("Este navegador não suporta criptografia segura.");
      return false;
    }

    // Chave pública (não precisa mudar isso)
    const pem = `-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnaBvmUcUSg+hkQoxfRRJ
QPL6NWh+CxxOSLvE6kw04AnD+LcxkfW5gBeE1jzSSE1vRHydEbI+sf62oxiPZDKO
jry+2JP7CbXmpBI3Sfof0KLikIZctAz8FuHDmbyS80FF0oFDBduAB6OAmM0ZbMBu
4Iz0yme767CH/gO2lzlV8Wx9ZDEG8a3lSKFAwZWp4YohW7RMI1cTl6e8beShPS56
aeXld89c47cB0Spn5th+YI9+GIuRZvYm3ChKLQd0zHJL2Br8qBzxNnIDVcAr06Zp
YSC2Bw7swmiQTqBj1HgAFewN1TV3PyoynZCE24RUu0P+aNU8oZb7KnyyaiqEK9dn
EQIDAQAB
-----END PUBLIC KEY-----`;

    const pemContents = pem
      .replace(/-----BEGIN PUBLIC KEY-----/, "")
      .replace(/-----END PUBLIC KEY-----/, "")
      .replace(/\s/g, "");

    const binaryDer = Uint8Array.from(atob(pemContents), c => c.charCodeAt(0));

    const key = await crypto.subtle.importKey(
      "spki",
      binaryDer.buffer,
      { name: "RSA-OAEP", hash: "SHA-256" },
      false,
      ["encrypt"]
    );

    const encoder = new TextEncoder();
    const usuario = document.getElementById("usuario").value;
    const senha = document.getElementById("senha").value;

    const usuarioEncrypted = await crypto.subtle.encrypt({ name: "RSA-OAEP" }, key, encoder.encode(usuario));
    const senhaEncrypted = await crypto.subtle.encrypt({ name: "RSA-OAEP" }, key, encoder.encode(senha));

    document.getElementById("usuario_seguro").value = btoa(String.fromCharCode(...new Uint8Array(usuarioEncrypted)));
    document.getElementById("senha_segura").value = btoa(String.fromCharCode(...new Uint8Array(senhaEncrypted)));

    // Evita envio dos dados visíveis
    document.getElementById("usuario").disabled = true;
    document.getElementById("senha").disabled = true;

    event.target.submit();
  }