function mudarEstado(ledId, novoEstado) {
    const params = "led_id=" + encodeURIComponent(ledId) + "&novo_estado=" + encodeURIComponent(novoEstado);

    fetch('/atualizar_estado.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: params
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Exibe a resposta do servidor no console
        location.reload(); // Recarrega a página para exibir o novo estado
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao comunicar com o servidor.');
    });
}