<?php

// Configurações do banco de dados
$servername = "localhost";
$username = "aplicacao_leds";
$password = "18071988";
$dbname = "cofre_iot";

function getDbConnection() {
    global $servername, $username, $password, $dbname;

    // Cria a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica a conexão
    if ($conn->connect_error) {
        throw new Exception("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    return $conn;
}
?>