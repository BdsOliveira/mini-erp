document.addEventListener("DOMContentLoaded", () => {
  cepField = document.getElementById("cep");

  cepField.addEventListener("input", () => {
    if (cepField.value.length > 9) {
      cepField.value = cepField.value.slice(0, 9);
      return;
    }

    let cep = cepField.value;
    cep = cep.replace(/\D/g, "");
    cep = cep.replace(/^(\d{5})(\d)/, "$1-$2");
    cepField.value = cep;
  });

  cepField.addEventListener("blur", async () => {
    await validateCep(cepField);
  });
});

async function validateCep(cepField) {
  fetch(`https://viacep.com.br/ws/${cepField.value}/json/`)
    .then((response) => response.json())
    .then((data) => {
      if (data.erro) {
        cepField.setCustomValidity("CEP inválido!");
        return;
      }

      cepField.setCustomValidity("");
      document.getElementById("cidade").value = data.localidade;
      document.getElementById("bairro").value = data.bairro;
      document.getElementById("endereco").value = data.logradouro;
      document.getElementById("estado").value = data.uf;
    });
}
