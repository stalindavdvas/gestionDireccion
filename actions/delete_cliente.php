<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idcli = $_POST['idcli'];

    try {
        $sql = "DELETE FROM clientes WHERE idcli = :idcli";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idcli', $idcli, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Éxito
            echo json_encode(['status' => 'success', 'message' => 'Cliente eliminado correctamente']);
        } else {
            // Error en ejecución
            echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el cliente.']);
        }
    } catch (PDOException $e) {
        // Error de base de datos
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}
?>
