<?php
require 'db_connection.php';

// Configuración de la tabla y la clave primaria
$table = 'clientes';  // Cambia 'clientes' a tu tabla real
$primaryKey = 'idcli'; // Asegúrate de que 'idcli' es la clave primaria de tu tabla

// Definir las columnas que serán leídas y enviadas a DataTables
$columns = array(
    array( 'db' => 'idcli', 'dt' => 0 ),
    array( 'db' => 'Pnombre', 'dt' => 1 ),
    array( 'db' => 'Snombre', 'dt' => 2 ),
    array( 'db' => 'Papellido', 'dt' => 3 ),
    array( 'db' => 'Sapellido', 'dt' => 4 ),
    array( 'db' => 'DNI', 'dt' => 5 ),
    array( 'db' => 'Telefono', 'dt' => 6 ),
    array( 'db' => 'Email', 'dt' => 7 ),
    array( 'db' => 'Genero', 'dt' => 8 ),
    array( 'db' => 'FechaNacimiento', 'dt' => 9 ),
    // Si necesitas una columna de acciones
    array(
        'db' => 'idcli',
        'dt' => 10,
        'formatter' => function( $d, $row ) {
            return '
            <button class="btn btn-sm btn-warning editar d-inline" data-id="'.$d.'"><i class="bi bi-pen"></i></button> 
                    <button class="btn btn-sm btn-danger eliminar d-inline" data-id="'.$d.'"><i class="bi bi-trash"></i></button>';
        }
    ),
);

// Detalles de la conexión a la base de datos (ajustados con tu configuración de PDO)
$sql_details = array(
    'user' => 'root',   // Reemplaza con tu usuario
    'pass' => '', // Reemplaza con tu contraseña
    'db'   => 'direcciones', // Reemplaza con tu base de datos
    'host' => 'localhost'      // Si estás usando localhost
);

// Requiere la clase SSP para procesar la solicitud
require('ssp.class.php');

// Devolver los resultados JSON con DataTables
echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
