function buscarProduto() {
    var query = document.getElementById("produto").value;
    if (query.length > 2) {
        window.location.href = "?query=" + query;
    }
}

// Função para preencher o campo do produto ao selecionar um produto da lista
function selecionarProduto(id, nome) {
    document.getElementById("produto").value = nome;
    document.getElementById("id_produto").value = id;
    document.getElementById("produto-lista").innerHTML = '';  // Limpar a lista de sugestões
}

// Envio do formulário via AJAX
document.getElementById("form-registrar-perca").addEventListener('submit', function(e) {
    e.preventDefault();  // Impede o envio do formulário padrão

    var formData = new FormData(this);

    fetch('registrar_perda.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        let mensagemDiv = document.getElementById("mensagem");
        mensagemDiv.innerHTML = data;
        mensagemDiv.classList.add("success"); // Exibe a mensagem retornada do PHP
    })
    .catch(error => {
        let mensagemDiv = document.getElementById("mensagem");
        mensagemDiv.innerHTML = "Erro ao registrar a perda.";
        mensagemDiv.classList.add("error");
        console.error('Erro ao registrar a perda:', error);
    });
});