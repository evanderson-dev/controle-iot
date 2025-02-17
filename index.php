<!DOCTYPE html>
<html>
<head>
    <title>Controle de LED (Estado)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Controle de LED (Estado)</h1>

    <?php
    include 'db.php'; // Inclui o arquivo com a lógica do banco de dados

    // Verifica se a conexão foi estabelecida
    if (isset($conn)) {
        try {
            $result = getLedStates($conn); // Obtém os estados dos LEDs

            // Exibe os estados dos LEDs
            while ($row = $result->fetch_assoc()) {
                echo "<p>LED " . substr($row["led_id"], 4) . ": <span id=\"" . $row["led_id"] . "\">" . htmlspecialchars($row["estado"]) . "</span></p>";

                echo "<button class=\"button\" onclick=\"mudarEstado('" . $row["led_id"] . "', 'ON')\">Ligar " . substr($row["led_id"], 4) . "</button>";
                echo "<button class=\"button\" onclick=\"mudarEstado('" . $row["led_id"] . "', 'OFF')\">Desligar " . substr($row["led_id"], 4) . "</button>";

                echo "<br><br>";
            }

            $conn->close(); // Fecha a conexão
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
    ?>

    <script src="script.js"></script>
</body>
</html>
