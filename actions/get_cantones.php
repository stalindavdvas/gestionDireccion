<?php
include('db_connection.php');
if (isset($_POST['id_provincia'])) {
    $idProvincia = $_POST['id_provincia'];
    $stmt = $conn->prepare("SELECT id_canton, canton FROM cantones WHERE id_provincia = :idProvincia");
    $stmt->bindParam(':idProvincia', $idProvincia, PDO::PARAM_INT);
    $stmt->execute();
    $cantones = $stmt->fetchAll();
    echo '<option value="" disabled selected>Selecciona un cantón</option>';
    foreach ($cantones as $canton) {
        echo "<option value='" . $canton['id_canton'] . "'>" . $canton['canton'] . "</option>";
    }
}
?>
