<?php
include 'db.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">";

try {
    $conn = getDbConnection();

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

<script src="script.js"></script>
</body>
</html>