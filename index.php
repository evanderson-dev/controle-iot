<!DOCTYPE html>
<html>
<head>
	<title>Controle de LED (Estado)</title>
	<style>
		.button {
			background-color: #4cAF50; /* Cor verde */
			border: none;
			color: white;
			padding: px 32px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 4px 2px;
			cursor: pointer;
			border-radius: 5px;
		}
	</style>
</head>
<body>
	<h1>Controle de LED (Estado)</h1>

    <?php

    // Configurações do banco de dados
    $servername = "localhost";
    $username = "aplicacao_leds";
    $password = "18071988";
    $dbname = "controle_leds";
    
    try {
        // Cria a conexão
        $conn = new mysqli($servername, $username, $password, $dbname);
    
        // Verifica a conexão
        if ($conn->connect_error) {
            throw new Exception("Falha na conexão com o banco de dados: " . $conn->connect_error);
        }
            
        $stmt = $conn->prepare("SELECT led_id, estado FROM leds");
        $stmt->execute();
        $result = $stmt->get_result();

	// Exibe os estados dos LEDs
        while ($row = $result->fetch_assoc()) {
            echo "<p>LED " . substr($row["led_id"], 4) . ": <span id=\"" . $row["led_id"] . "\">" . htmlspecialchars($row["estado"]) . "</span></p>";
            echo "<button class=\"button\" onclick=\"mudarEstado('" . $row["led_id"] . "', 'ON')\">Ligar " . substr($row["led_id"], 4) . "</button>";
            echo "<button class=\"button\" onclick=\"mudarEstado('" . $row["led_id"] . "', 'OFF')\">Desligar " . substr($row["led_id"], 4) . "</button>";
            echo "<br><br>";
        }

        $stmt->close();
        $conn->close();
    
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
    ?>
	
    <script>
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
	</script>
</body>
</html>
