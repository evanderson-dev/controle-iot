<?php
session_start();
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define o tipo de conteúdo como JSON
header('Content-Type: application/json');

// Verificação de login (desativada para testes)
//if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
//    http_response_code(401);
//    echo json_encode(["error" => "Acesso não autorizado"]);
//    exit;
//}

try {
    $conn = getDbConnection();
    $conn->set_charset("utf8"); // Garante codificação correta

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Debug: Log do início do GET
        file_put_contents('debug.log', "GET iniciado: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

        $stmt = $conn->prepare("SELECT estado FROM cofre WHERE id = 1");
        if (!$stmt) {
            throw new Exception("Erro ao preparar a query: " . $conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Erro ao executar a query: " . $stmt->error);
        }

        $row = $result->fetch_assoc();
        if ($row) {
            $response = ["estado" => $row["estado"] ?? "bloqueado"];
        } else {
            http_response_code(404);
            $response = ["error" => "Cofre não encontrado"];
        }

        // Debug: Log do fim do GET
        file_put_contents('debug.log', "GET concluído: " . json_encode($response) . "\n", FILE_APPEND);

        echo json_encode($response);
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["id"]) && isset($_POST["novo_estado"])) {
            $id = $_POST["id"];
            $novo_estado = $_POST["novo_estado"];

            if (!in_array($novo_estado, ["bloqueado", "desbloqueado"])) {
                http_response_code(400);
                echo json_encode(["error" => "Estado inválido. Use 'bloqueado' ou 'desbloqueado'"]);
                exit;
            }

            $stmt = $conn->prepare("UPDATE cofre SET estado = ? WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Erro ao preparar a query: " . $conn->error);
            }

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
    file_put_contents('debug.log', "Erro: " . $e->getMessage() . " - " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
}
?>