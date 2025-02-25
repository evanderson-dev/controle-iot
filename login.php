<?php
session_start();
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$estado_cofre = '';

try {
    $conn = getDbConnection();

    // Busca o estado do cofre no banco de dados
    $stmt = $conn->prepare("SELECT estado FROM cofre WHERE id = 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $estado_cofre = htmlspecialchars($row["estado"]);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    $error = "Erro: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senha = $_POST["senha"];

    try {
        $conn = getDbConnection();

        // Verifica a senha no banco de dados
        $stmt = $conn->prepare("SELECT senha FROM cofre WHERE id = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row && password_verify($senha, $row["senha"])) {
            $_SESSION["loggedin"] = true;
            header("Location: index.php");
            exit;
        } else {
            $error = "Senha incorreta.";
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        $error = "Erro: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Smart Vault - Login</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit" class="button">Login</button>
        </form>
        <div class="cofre-status">
            <p>Status do Cofre: <span><?php echo $estado_cofre; ?></span></p>
        </div>
    </div>
</body>
</html>