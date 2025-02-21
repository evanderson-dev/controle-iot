<?php
include 'db.php';

header('Content-Type: text/html');

try {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT estado FROM cofre WHERE id = 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $estado = $row["estado"] ?? "bloqueado";
    echo "<span id=\"cofre_1\">" . htmlspecialchars($estado) . "</span>";
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo "<span id=\"cofre_1\">bloqueado</span>"; // Estado padrão em caso de erro
}
?>