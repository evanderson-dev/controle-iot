<?php
include 'db.php';
header('Content-Type: application/json');

// Força a saída imediata
ob_start();
echo "Teste inicial\n"; // Testa saída simples
ob_flush();
flush();

file_put_contents('debug.log', "GET funcionando: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

// Testa JSON
$response = ["estado" => "teste"];
echo json_encode($response);
ob_flush();
flush();
?>