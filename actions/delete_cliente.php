<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idcli = $_POST['idcli'];
    try {
        // Intentar eliminar el cliente
        $sql = "DELETE FROM clientes WHERE idcli = :idcli";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idcli', $idcli, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Éxito: Cliente eliminado correctamente
            echo json_encode(['status' => 'success', 'message' => 'Cliente eliminado correctamente']);
        } else {
            // Error en ejecución (general)
            echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el cliente.']);
        }
    } catch (PDOException $e) {
        // Capturar el error específico de clave foránea
        if (strpos($e->getMessage(), 'Integrity constraint violation') !== false) {
            // Mensaje personalizado para violación de clave foránea
            echo json_encode([
                'status' => 'error',
                'message' => 'Error: El cliente posee direcciones asociadas. Elimine primero todas las direcciones del cliente para poder eliminarlo.'
            ]);
        } else {
            // Otro tipo de error de base de datos
            echo json_encode([
                'status' => 'error',
                'message' => 'Error en la base de datos: ' . $e->getMessage()
            ]);
        }
    }
}
?>