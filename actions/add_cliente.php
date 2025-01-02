<?php
require 'db_connection.php';
// Verificar si se enviaron los datos necesarios
if (isset($_POST['Pnombre']) && isset($_POST['Snombre']) && isset($_POST['Papellido'])&& isset($_POST['Sapellido'])
 && isset($_POST['DNI']) && isset($_POST['Telefono'])&& isset($_POST['Email'])&& isset($_POST['Genero'])&& isset($_POST['FecNac'])) {
    
    // Capturar datos del formulario
    $Pnombre = $_POST['Pnombre'];
    $Snombre = $_POST['Snombre'];
    $Papellido = $_POST['Papellido'];
    $Sapellido = $_POST['Sapellido'];
    $DNI = $_POST['DNI'];
    $Telefono = $_POST['Telefono'];
    $Email = $_POST['Email'];
    $Genero = $_POST['Genero'];
    $FechaNacimiento = $_POST['FecNac'];

    try {
        // Preparar la consulta SQL usando PDO
        $sql = "INSERT INTO clientes (Pnombre, Snombre, Papellido,Sapellido, DNI,Telefono,Email,Genero, FechaNacimiento) VALUES (:Pnombre, :Snombre, :Papellido,:Sapellido, :DNI,:Telefono,:Email,:Genero,:FechaNacimiento)";
        $stmt = $conn->prepare($sql);

        // Ejecutar la consulta y enlazar los parámetros
        $stmt->execute([
            ':Pnombre' => $Pnombre,
            ':Snombre' => $Snombre,
            ':Papellido' => $Papellido,
            ':Sapellido' => $Sapellido,
            ':DNI' => $DNI,
            ':Telefono' => $Telefono,
            ':Email' => $Email,
            ':Genero' => $Genero,
            ':FechaNacimiento' => $FechaNacimiento
        ]);

        // Si todo sale bien, devolver una respuesta de éxito en JSON
        echo json_encode(['status' => 'success', 'message' => 'Cliente agregado exitosamente']);
    } catch (PDOException $e) {
        // Si ocurre un error durante la ejecución de la consulta, devolver un error en JSON
        echo json_encode(['status' => 'error', 'message' => 'Error al agregar cliente: ' . $e->getMessage()]);
    }
} else {
    // Si faltan parámetros, devolver un error
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos para agregar el cliente']);
}

// Cerrar la conexión PDO (opcional, PDO cierra la conexión automáticamente al finalizar el script)
$conn = null;
?>
