<?php
include('db_connection.php');
if (isset($_POST['id_canton'])) {
    $idCanton = $_POST['id_canton'];
    $stmt = $conn->prepare("SELECT id_parroquia, parroquia FROM parroquias WHERE id_canton = :idCanton");
    $stmt->bindParam(':idCanton', $idCanton, PDO::PARAM_INT);
    $stmt->execute();
    $parroquias = $stmt->fetchAll();
    echo '<option value="" disabled selected>Selecciona una parroquia</option>';
    foreach ($parroquias as $parroquia) {
        echo "<option value='" . $parroquia['id_parroquia'] . "'>" . $parroquia['parroquia'] . "</option>";
    }
}
?>
