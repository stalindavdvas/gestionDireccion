<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir el ID de la dirección
    $iddir = $_POST['iddir'];

    // Preparar la consulta de eliminación
    $sql = "DELETE FROM direcciones WHERE iddir = :iddir";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':iddir', $iddir, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Dirección eliminada exitosamente']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar la dirección']);
    }
}
?>
