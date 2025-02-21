<?php
session_start();
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define o tipo de conteúdo como JSON
header('Content-Type: application/json');

// Verificação de login (desativada para testes, ativar em produção)
//if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
//    http_response_code(401);
//    echo json_encode(["error" => "Acesso não autorizado"]);
//    exit;
//}

try {
    $conn = getDbConnection();

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $stmt = $conn->prepare("SELECT estado FROM cofre WHERE id = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            echo json_encode(["estado" => $row["estado"] ?? "bloqueado"]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Cofre não encontrado"]);
        }
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["id"]) && isset($_POST["novo_estado"])) {
            $id = $_POST["id"];
            $novo_estado = $_POST["novo_estado"];

            // Validação do estado
            if (!in_array($novo_estado, ["bloqueado", "desbloqueado"])) {
                http_response_code(400);
                echo json_encode(["error" => "Estado inválido. Use 'bloqueado' ou 'desbloqueado'"]);
                exit;
            }

            $stmt = $conn->prepare("UPDATE cofre SET estado = ? WHERE id = ?");
            $stmt->bind_param("si", $novo_estado, $id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Estado atualizado para $novo_estado"]);
            } else {
                throw new Exception("Erro ao atualizar o estado: " . $stmt->error);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Dados incompletos"]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro interno: " . $e->getMessage()]);
}
?>