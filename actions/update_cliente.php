<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idcli = $_POST['idcli'];
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
        $sql = "UPDATE clientes SET Pnombre = :Pnombre, Snombre = :Snombre, Papellido = :Papellido, Sapellido = :Sapellido, DNI = :DNI,Telefono=:Telefono,Email=:Email,Genero=:Genero,FechaNacimiento=:FechaNacimiento  WHERE idcli = :idcli";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':Pnombre', $Pnombre, PDO::PARAM_STR);
        $stmt->bindParam(':Snombre', $Snombre, PDO::PARAM_STR);
        $stmt->bindParam(':Papellido', $Papellido, PDO::PARAM_STR);
        $stmt->bindParam(':Sapellido', $Sapellido, PDO::PARAM_STR);
        $stmt->bindParam(':DNI', $DNI, PDO::PARAM_STR);
        $stmt->bindParam(':Telefono', $Telefono, PDO::PARAM_STR);
        $stmt->bindParam(':Email', $Email, PDO::PARAM_STR);
        $stmt->bindParam(':Genero', $Genero, PDO::PARAM_STR);
        $stmt->bindParam(':FechaNacimiento', $FechaNacimiento, PDO::PARAM_STR);
        $stmt->bindParam(':idcli', $idcli, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Éxito
            echo json_encode(['status' => 'success', 'message' => 'Cliente actualizado correctamente']);
        } else {
            // Error en ejecución
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el cliente.']);
        }
    } catch (PDOException $e) {
        // Error de base de datos
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}
?>
