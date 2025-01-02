<?php
require 'db_connection.php';
// Verificar si se enviaron los datos necesarios
if (isset($_POST['Pnombre']) && isset($_POST['Snombre']) && isset($_POST['Papellido']) && isset($_POST['Sapellido'])
    && isset($_POST['DNI']) && isset($_POST['Telefono']) && isset($_POST['Email']) && isset($_POST['Genero']) && isset($_POST['FecNac'])) {

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

    function validarCedulaEcuatoriana($cedula) {
        if (strlen($cedula) != 10) {
            return false;
        }
        $provincia = intval(substr($cedula, 0, 2));
        if ($provincia < 1 || ($provincia > 24 && $provincia != 30)) {
            return false;
        }
        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $suma = 0;
        for ($i = 0; $i < 9; $i++) {
            $digito = intval($cedula[$i]) * $coeficientes[$i];
            if ($digito >= 10) {
                $digito -= 9;
            }
            $suma += $digito;
        }
        $digitoVerificador = intval($cedula[9]);
        $sumaMod10 = $suma % 10 == 0 ? 0 : 10 - ($suma % 10);
        return $sumaMod10 == $digitoVerificador;
    }

    // Validar la cédula antes de insertar
    if (!validarCedulaEcuatoriana($DNI)) {
        echo json_encode(['status' => 'error', 'message' => 'La cédula ingresada es inválida.']);
        exit; // Salir si la cédula es inválida
    }

    try {
        $sql = "INSERT INTO clientes (Pnombre, Snombre, Papellido, Sapellido, DNI, Telefono, Email, Genero, FechaNacimiento)
                VALUES (:Pnombre, :Snombre, :Papellido, :Sapellido, :DNI, :Telefono, :Email, :Genero, :FechaNacimiento)";
        $stmt = $conn->prepare($sql);

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

        echo json_encode(['status' => 'success', 'message' => 'Cliente agregado exitosamente']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error al agregar cliente: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos para agregar el cliente']);
}

$conn = null;
?>
