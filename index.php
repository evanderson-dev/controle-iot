<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de LEDs</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Controle de LEDs</h1>
        <?php
        try {
            $conn = getDbConnection();

            $stmt = $conn->prepare("SELECT led_id, estado FROM leds");
            $stmt->execute();
            $result = $stmt->get_result();

            // Exibe os estados dos LEDs
            while ($row = $result->fetch_assoc()) {
                echo "<div class=\"led-control\">";
                echo "<p>LED " . substr($row["led_id"], 4) . ": <span id=\"" . $row["led_id"] . "\">" . htmlspecialchars($row["estado"]) . "</span></p>";
                echo "<button class=\"button\" onclick=\"mudarEstado('" . $row["led_id"] . "', 'ON')\">Ligar</button>";
                echo "<button class=\"button\" onclick=\"mudarEstado('" . $row["led_id"] . "', 'OFF')\">Desligar</button>";
                echo "</div>";
            }

            $stmt->close();
            $conn->close();

        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
        ?>
    </div>
    <script src="script.js"></script>
</body>
</html>