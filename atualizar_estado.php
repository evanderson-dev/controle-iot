<?php
include 'db.php';
header('Content-Type: application/json');
file_put_contents('debug.log', "GET funcionando: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
echo json_encode(["estado" => "teste"]);
?>