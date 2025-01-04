<?php
include('db_connection.php');
$stmt = $conn->prepare("SELECT id_provincia, provincia FROM provincias");
$stmt->execute();
$provincias = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($provincias);
?>
