<?php
session_start();
include 'db.php';

// Comente as linhas abaixo para desabilitar a verificação de login
// if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
//     header("Location: login.php");
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle do Cofre</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Controle do Cofre</h1>
        <?php
        try {
            $conn = getDbConnection();
            
            $stmt = $conn->prepare("SELECT id, estado FROM cofre");
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Exibe os estados do cofre
            while ($row = $result->fetch_assoc()) {
                echo "<div class=\"cofre-control\">";
                echo "<p>Status: <span  class=\"status-display\"  id=\"cofre_" . $row["id"] . "\">" . htmlspecialchars($row["estado"]) . "</span></p>";
                echo "<button class=\"button\" onclick=\"mudarEstado(" . $row["id"] . ", 'desbloqueado')\">Desbloquear</button>";
                echo "<button class=\"button\" onclick=\"mudarEstado(" . $row["id"] . ", 'bloqueado')\">Bloquear</button>";
                echo "</div>";
                echo "<br><br>";
            }
            
            $stmt->close();
            $conn->close();
            
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
        ?>
        <div class="others-buttons-container">
            <a href="alterar_senha.php" class="button">Alterar Senha</a><br>
            <a href="logout.php" class="button">Logoff</a><br>            
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>