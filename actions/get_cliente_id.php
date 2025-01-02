<?php
require 'db_connection.php';

if (isset($_POST['idcli'])) {
    $idcli = $_POST['idcli'];

    // Consulta SQL para obtener los datos del cliente
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE idcli = :idcli");
    $stmt->bindParam(':idcli', $idcli, PDO::PARAM_INT);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica si se encontraron los datos del cliente
    if ($cliente) {
        echo json_encode($cliente); // Devolver datos en formato JSON
    } else {
        echo json_encode(['error' => 'No se encontraron los datos del cliente.']);
    }
}
?>
