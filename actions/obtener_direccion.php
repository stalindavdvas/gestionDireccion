<?php
include('db_connection.php');

if (isset($_GET['iddir'])) {
    $iddir = $_GET['iddir'];

    // Preparar la consulta para obtener la dirección
    $sql = "SELECT d.*, p.provincia AS provincia, c.canton AS canton, pa.parroquia AS parroquia
            FROM direcciones d
            LEFT JOIN provincias p ON d.id_provincia = p.id_provincia
            LEFT JOIN cantones c ON d.id_canton = c.id_canton
            LEFT JOIN parroquias pa ON d.id_parroquia = pa.id_parroquia
            WHERE d.iddir = :iddir";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':iddir', $iddir, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $direccion = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($direccion) {
            // Devolver los datos en formato JSON
            echo json_encode([
                'status' => 'success',
                'data' => $direccion
            ]);
        } else {
            // Si no se encuentra la dirección
            echo json_encode([
                'status' => 'error',
                'message' => 'Dirección no encontrada'
            ]);
        }
    } else {
        // Error en la consulta
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener los datos de la dirección'
        ]);
    }
} else {
    // Si no se ha proporcionado el iddir
    echo json_encode([
        'status' => 'error',
        'message' => 'ID de dirección no proporcionado'
    ]);
}
?>
