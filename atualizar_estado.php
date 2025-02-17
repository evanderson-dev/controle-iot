<?php

// Configurações do banco de dados
$servername = "localhost";
$username = "aplicacao_leds";
$password = "18071988";
$dbname = "controle_leds";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $led_id = $_POST["led_id"];
    $novo_estado = $_POST["novo_estado"];

    try {
        // Cria a conexão
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verifica a conexão
        if ($conn->connect_error) {
            throw new Exception("Falha na conexão com o banco de dados: " . $conn->connect_error);
        }

        // Prepara e executa a query
        $stmt = $conn->prepare("UPDATE leds SET estado = ? WHERE led_id = ?");
        $stmt->bind_param("ss", $novo_estado, $led_id);  // "ss" indica dois parâmetros string

        if ($stmt->execute()) {
            echo "Estado do LED " . htmlspecialchars($led_id) . " atualizado para " . htmlspecialchars($novo_estado) . " no banco de dados.";
        } else {
            throw new Exception("Erro ao atualizar o estado do LED: " . $stmt->error);
        }

        // Fecha a conexão
        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }

} else {
    echo "Acesso inválido.";
}

?>
