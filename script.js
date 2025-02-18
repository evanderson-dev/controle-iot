function mudarEstado(id, novoEstado) {
    console.log("mudarEstado chamado com id:", id, "e novoEstado:", novoEstado); // Adicione este log

    const params = "id=" + encodeURIComponent(id) + "&novo_estado=" + encodeURIComponent(novoEstado);

    fetch('/atualizar_estado.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: params
    })
    .then(response => response.text())
    .then(data => {
        console.log("Resposta do servidor:", data); // Adicione este log
        location.reload(); // Recarrega a página para exibir o novo estado
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao comunicar com o servidor.');
    });
}