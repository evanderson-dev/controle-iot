function mudarEstado(id, novoEstado) {
    const params = "id=" + encodeURIComponent(id) + "&novo_estado=" + encodeURIComponent(novoEstado);

    fetch('atualizar_estado.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: params
    })
    .then(response => {
        if (!response.ok) throw new Error('Erro HTTP: ' + response.status);
        return response.json(); // Ajustado para JSON
    })
    .then(data => {
        console.log("Resposta do servidor:", data);
        const estadoSpan = document.getElementById("cofre_" + id);
        const botao = document.getElementById("botao_" + id);

        if (data.success) {
            // Atualiza a interface com o novo estado
            estadoSpan.textContent = novoEstado;
            const textoBotao = novoEstado === 'desbloqueado' ? 'Bloquear' : 'Desbloquear';
            const proximoEstado = novoEstado === 'desbloqueado' ? 'bloqueado' : 'desbloqueado';
            botao.textContent = textoBotao;
            botao.setAttribute('onclick', "mudarEstado(" + id + ", '" + proximoEstado + "')");
        } else {
            console.error("Erro do servidor:", data.error);
            alert('Erro ao atualizar o estado: ' + data.error);
            sincronizarEstado(id); // Reverte o estado em caso de erro
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
        alert('Ocorreu um erro ao comunicar com o servidor.');
        sincronizarEstado(id); // Reverte o estado em caso de erro
    });
}

// Sincroniza o estado com o banco
function sincronizarEstado(id) {
    fetch('atualizar_estado.php', {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        const estadoSpan = document.getElementById("cofre_" + id);
        const botao = document.getElementById("botao_" + id);
        estadoSpan.textContent = data.estado;
        const textoBotao = data.estado === 'bloqueado' ? 'Desbloquear' : 'Bloquear';
        const proximoEstado = data.estado === 'bloqueado' ? 'desbloqueado' : 'bloqueado';
        botao.textContent = textoBotao;
        botao.setAttribute('onclick', "mudarEstado(" + id + ", '" + proximoEstado + "')");
    })
    .catch(error => console.error('Erro ao sincronizar:', error));
}

// Sincroniza todos os cofres ao carregar a página
window.onload = function() {
    const cofres = document.querySelectorAll('[id^="cofre_"]');
    cofres.forEach(cofre => {
        const id = cofre.id.replace('cofre_', '');
        sincronizarEstado(id);
    });
};