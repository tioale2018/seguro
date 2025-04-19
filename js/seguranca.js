document.addEventListener("DOMContentLoaded", () => {
    const publicKeyPEM = `-----BEGIN PUBLIC KEY-----
    MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnaBvmUcUSg+hkQoxfRRJ
    QPL6NWh+CxxOSLvE6kw04AnD+LcxkfW5gBeE1jzSSE1vRHydEbI+sf62oxiPZDKO
    jry+2JP7CbXmpBI3Sfof0KLikIZctAz8FuHDmbyS80FF0oFDBduAB6OAmM0ZbMBu
    4Iz0yme767CH/gO2lzlV8Wx9ZDEG8a3lSKFAwZWp4YohW7RMI1cTl6e8beShPS56
    aeXld89c47cB0Spn5th+YI9+GIuRZvYm3ChKLQd0zHJL2Br8qBzxNnIDVcAr06Zp
    YSC2Bw7swmiQTqBj1HgAFewN1TV3PyoynZCE24RUu0P+aNU8oZb7KnyyaiqEK9dn
    EQIDAQAB
    -----END PUBLIC KEY-----`;
  
    document.querySelectorAll("form").forEach(form => {
      form.addEventListener("submit", async (event) => {
        event.preventDefault();
  
        if (!window.crypto || !window.crypto.subtle) {
          alert("Este navegador não suporta criptografia segura.");
          return false;
        }
  
        const pemContents = publicKeyPEM.replace(/-----.*?-----/g, "").replace(/\s/g, "");
        const binaryDer = Uint8Array.from(atob(pemContents), c => c.charCodeAt(0));
        const key = await crypto.subtle.importKey(
          "spki",
          binaryDer.buffer,
          { name: "RSA-OAEP", hash: "SHA-256" },
          false,
          ["encrypt"]
        );
  
        const encoder = new TextEncoder();
  
        // Criptografa todos os campos do formulário
        const inputs = form.querySelectorAll("input, textarea, select");
        for (const input of inputs) {
          if (!input.name || input.type === "hidden" || input.disabled) continue;
  
          const encrypted = await crypto.subtle.encrypt(
            { name: "RSA-OAEP" },
            key,
            encoder.encode(input.value)
          );
  
          const encoded = btoa(String.fromCharCode(...new Uint8Array(encrypted)));
  
          // Cria um campo hidden com o conteúdo criptografado
          const hidden = document.createElement("input");
          hidden.type = "hidden";
          hidden.name = input.name + "_seguro";
          hidden.value = encoded;
          form.appendChild(hidden);
  
          // Desativa o campo original
          input.disabled = true;
        }
  
        form.submit();
      });
    });
  });
  