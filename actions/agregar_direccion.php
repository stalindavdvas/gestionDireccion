<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir los datos del formulario
    $idcli = $_POST['idcli'];
    $id_provincia = $_POST['id_provincia'];
    $id_canton = $_POST['id_canton'];
    $id_parroquia = $_POST['id_parroquia'];
    $CallePrincipal = $_POST['CallePrincipal'];
    $CalleSecundaria = $_POST['CalleSecundaria'];
    $Numero = $_POST['Numero'];
    $Lugar = $_POST['tipo'];
    $CodigoPostal = $_POST['CodigoPostal'];

    // Preparar la consulta de inserción
    $sql = "INSERT INTO direcciones (idcli, id_provincia, id_canton, id_parroquia, CallePrincipal, CalleSecundaria, Numero, Lugar, CodigoPostal) 
            VALUES (:idcli, :id_provincia, :id_canton, :id_parroquia, :CallePrincipal, :CalleSecundaria, :Numero, :Lugar, :CodigoPostal)";
    
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bindParam(':idcli', $idcli, PDO::PARAM_INT);
    $stmt->bindParam(':id_provincia', $id_provincia, PDO::PARAM_INT);
    $stmt->bindParam(':id_canton', $id_canton, PDO::PARAM_INT);
    $stmt->bindParam(':id_parroquia', $id_parroquia, PDO::PARAM_INT);
    $stmt->bindParam(':CallePrincipal', $CallePrincipal, PDO::PARAM_STR);
    $stmt->bindParam(':CalleSecundaria', $CalleSecundaria, PDO::PARAM_STR);
    $stmt->bindParam(':Numero', $Numero, PDO::PARAM_STR);
    $stmt->bindParam(':Lugar', $Lugar, PDO::PARAM_STR);
    $stmt->bindParam(':CodigoPostal', $CodigoPostal, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Dirección agregada exitosamente']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al agregar la dirección']);
    }
}
?>
