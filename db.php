<?php

// Configurações do banco de dados
$servername = "localhost";
$username = "aplicacao_leds";
$password = "sua_senha_segura"; // Substitua pela senha correta
$dbname = "controle_leds";

try {
    // Cria a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica a conexão
    if ($conn->connect_error) {
        throw new Exception("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Função para obter os estados dos LEDs
    function getLedStates($conn) {
        $stmt = $conn->prepare("SELECT led_id, estado FROM leds");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

} catch (Exception $e) {
    echo "Erro de conexão com o banco de dados: " . $e->getMessage();
    exit; // Encerra o script se houver um erro de conexão
}
?>
