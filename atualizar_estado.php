<?php
session_start();
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "Acesso não autorizado.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id"]) && isset($_POST["novo_estado"])) {
        $id = $_POST["id"];
        $novo_estado = $_POST["novo_estado"];

        try {
            $conn = getDbConnection();

            // Prepara e executa a query
            $stmt = $conn->prepare("UPDATE cofre SET estado = ? WHERE id = ?");
            $stmt->bind_param("si", $novo_estado, $id);  // "si" indica um parâmetro string e um inteiro

            if ($stmt->execute()) {
                echo "Estado do cofre " . htmlspecialchars($id) . " atualizado para " . htmlspecialchars($novo_estado) . " no banco de dados.";
            } else {
                throw new Exception("Erro ao atualizar o estado do cofre: " . $stmt->error);
            }

            // Fecha a conexão
            $stmt->close();
            $conn->close();

        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    } else {
        echo "Dados incompletos.";
    }
} else {
    echo "Acesso inválido.";
}
?>