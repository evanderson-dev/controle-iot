<?php
session_start();
include 'db.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senha_atual = $_POST["senha_atual"];
    $nova_senha = $_POST["nova_senha"];
    $confirmar_senha = $_POST["confirmar_senha"];

    if ($nova_senha !== $confirmar_senha) {
        $error = "A nova senha e a confirmação de senha não coincidem.";
    } else {
        try {
            $conn = getDbConnection();

            // Verifica a senha atual no banco de dados
            $stmt = $conn->prepare("SELECT senha FROM cofre WHERE id = 1");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row && password_verify($senha_atual, $row["senha"])) {
                // Atualiza a senha no banco de dados
                $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE cofre SET senha = ? WHERE id = 1");
                $stmt->bind_param("s", $nova_senha_hash);

                if ($stmt->execute()) {
                    $success = "Senha alterada com sucesso.";
                } else {
                    throw new Exception("Erro ao atualizar a senha: " . $stmt->error);
                }
            } else {
                $error = "Senha atual incorreta.";
            }

            $stmt->close();
            $conn->close();

        } catch (Exception $e) {
            $error = "Erro: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Alterar Senha</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <form method="post" action="alterar_senha.php">
            <input type="password" name="senha_atual" placeholder="Senha Atual" required>
            <input type="password" name="nova_senha" placeholder="Nova Senha" required>
            <input type="password" name="confirmar_senha" placeholder="Confirmar Nova Senha" required>
            <button type="submit" class="button">Alterar Senha</button>
        </form>
        <a href="index.php" class="button">Voltar</a>
        <a href="logout.php" class="button">Logoff</a>
    </div>
</body>
</html>