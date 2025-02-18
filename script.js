function mudarEstado(id, novoEstado) {
    const params = "id=" + encodeURIComponent(id) + "&novo_estado=" + encodeURIComponent(novoEstado);

    fetch('atualizar_estado.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: params
    })
    .then(response => response.text())
    .then(data => {
        console.log("Resposta do servidor:", data); // Exibe a resposta do servidor no console
        const estadoSpan = document.getElementById("cofre_" + id);
        const botao = document.getElementById("botao_" + id);
        if (novoEstado === 'desbloqueado') {
            estadoSpan.textContent = 'desbloqueado';
            botao.textContent = 'Bloquear';
            botao.setAttribute('onclick', "mudarEstado(" + id + ", 'bloqueado')");
        } else {
            estadoSpan.textContent = 'bloqueado';
            botao.textContent = 'Desbloquear';
            botao.setAttribute('onclick', "mudarEstado(" + id + ", 'desbloqueado')");
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao comunicar com o servidor.');
    });
}