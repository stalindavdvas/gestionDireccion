<?php
include('db_connection.php');

// Consulta para obtener las direcciones
$stmt = $conn->prepare("SELECT id, provincia, canton, parroquia, calle_principal, calle_secundaria, numero, codigo_postal FROM direcciones");
$stmt->execute();
$direcciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver los resultados como JSON
echo json_encode($direcciones);
?>
