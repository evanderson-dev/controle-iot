<?php
include 'db.php';

try {
    $conn = getDbConnection();

    // Criptografa a senha padrão e armazena no banco de dados
    $senha = password_hash("senha123", PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE cofre SET senha = ? WHERE id = 1");
    $stmt->bind_param("s", $senha);

    if ($stmt->execute()) {
        echo "Senha padrão configurada com sucesso.";
    } else {
        throw new Exception("Erro ao configurar a senha padrão: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>