<?php
require 'db_connection.php';

header('Content-Type: application/json');

// Iniciar la respuesta por defecto con estado de error
$response = [
    "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
    "recordsTotal" => 0,
    "recordsFiltered" => 0,
    "data" => [],
    "error" => ''
];

try {
    // Obtener los parámetros enviados por DataTables
    $limit = isset($_POST['length']) ? intval($_POST['length']) : 10; // Número de registros por página
    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;  // Desplazamiento (inicio de la paginación)
    $searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : ''; // Valor de búsqueda

    // Inicializar la consulta base
    $sqlCount = "SELECT COUNT(*) as total FROM clientes";
    $sql = "SELECT * FROM clientes";

    $params = [];
    $whereConditions = [];

    // Si hay un valor de búsqueda, agregar un filtro a todas las columnas
    if (!empty($searchValue)) {
        $searchPlaceholder = "%$searchValue%";
        $whereConditions[] = "Pnombre LIKE :searchValue";
        $whereConditions[] = "Snombre LIKE :searchValue";
        $whereConditions[] = "Papellido LIKE :searchValue";
        $whereConditions[] = "DNI LIKE :searchValue";
        $whereConditions[] = "Telefono LIKE :searchValue";
        $whereConditions[] = "Email LIKE :searchValue";
        $whereConditions[] = "Genero LIKE :searchValue";

        $params[":searchValue"] = $searchPlaceholder;
    }

    // Si hay filtros activos, agregarlos a la consulta
    if (!empty($whereConditions)) {
        $sqlCount .= " WHERE " . implode(" OR ", $whereConditions);
        $sql .= " WHERE " . implode(" OR ", $whereConditions);
    }

    // Contar los registros totales
    $stmt = $conn->prepare($sqlCount);
    $stmt->execute($params);
    $totalData = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Añadir paginación a la consulta
    $sql .= " LIMIT :start, :limit";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

    // Vincular los parámetros de búsqueda
    if (count($params) > 0) {
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
    }

    // Ejecutar la consulta de datos
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Preparar el resultado para DataTables
    $response = [
        "draw" => intval($_POST['draw']),
        "recordsTotal" => $totalData,        // Total de registros en la base de datos
        "recordsFiltered" => $totalData,     // Total de registros filtrados por la búsqueda
        "data" => $data                      // Datos para mostrar en la tabla
    ];
} catch (PDOException $e) {
    // En caso de error, capturar el mensaje y devolverlo en la respuesta
    $response['error'] = "Error en la consulta: " . $e->getMessage();
    error_log($e->getMessage()); // Opcional: para guardar en el log del servidor
}

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>
