<?php
include 'db.php';

// Define o tipo de conteúdo como JSON
header('Content-Type: application/json');

// Debug: Log inicial
file_put_contents('debug.log', "Script iniciado: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

try {
    $conn = getDbConnection();
    file_put_contents('debug.log', "Conexão MySQL estabelecida: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        file_put_contents('debug.log', "GET iniciado: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

        $result = $conn->query("SELECT estado FROM cofre WHERE id = 1");
        if ($result === false) {
            throw new Exception("Erro na query: " . $conn->error);
        }

        $row = $result->fetch_assoc();
        if ($row) {
            $response = ["estado" => $row["estado"] ?? "bloqueado"];
        } else {
            http_response_code(404);
            $response = ["error" => "Cofre não encontrado"];
        }

        file_put_contents('debug.log', "GET concluído: " . json_encode($response) . "\n", FILE_APPEND);
        echo json_encode($response);

        $result->free();
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
            $stmt->close();
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Dados incompletos"]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
    }

    $conn->close();
    file_put_contents('debug.log', "Conexão fechada: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro interno: " . $e->getMessage()]);
    file_put_contents('debug.log', "Erro: " . $e->getMessage() . " - " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
}
?>