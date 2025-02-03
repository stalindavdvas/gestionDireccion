<?php
require 'db_connection.php';

if (isset($_GET['idcli'])) {
    $idcli = $_GET['idcli'];

    // Aquí realizas la consulta SQL
    $stmt = $conn->prepare("
        SELECT d.iddir, p.provincia, c.canton, pa.parroquia, d.CallePrincipal, d.CalleSecundaria, d.Numero, d.Lugar, d.CodigoPostal
        FROM direcciones d
        JOIN provincias p ON d.id_provincia = p.id_provincia
        JOIN cantones c ON d.id_canton = c.id_canton
        JOIN parroquias pa ON d.id_parroquia = pa.id_parroquia
        WHERE d.idcli = :idcli
    ");
    $stmt->execute(['idcli' => $idcli]);
    $direcciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si se encontraron direcciones
    if (empty($direcciones)) {
        // Retornar un mensaje indicando que no hay direcciones
        echo '<div class="alert alert-warning">No existen direcciones ingresadas para este cliente.</div>';
        exit();
    }

    // Generar el HTML para mostrar las direcciones
    $html = '<table class="table table-striped">';
    $html .= '<thead><tr><th>Provincia</th><th>Cantón</th><th>Parroquia</th><th>Calle Principal</th><th>Calle Secundaria</th><th>Número</th><th>Lugar</th><th>Código Postal</th></tr></thead><tbody>';
    
    foreach ($direcciones as $direccion) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($direccion['provincia']) . '</td>';
        $html .= '<td>' . htmlspecialchars($direccion['canton']) . '</td>';
        $html .= '<td>' . htmlspecialchars($direccion['parroquia']) . '</td>';
        $html .= '<td>' . htmlspecialchars($direccion['CallePrincipal']) . '</td>';
        $html .= '<td>' . htmlspecialchars($direccion['CalleSecundaria']) . '</td>';
        $html .= '<td>' . htmlspecialchars($direccion['Numero']) . '</td>';
        $html .= '<td>' . htmlspecialchars($direccion['Lugar']) . '</td>';
        $html .= '<td>' . htmlspecialchars($direccion['CodigoPostal']) . '</td>';
        $html .= '</tr>';
    }
    
    $html .= '</tbody></table>';
    
    // Retornar el HTML al frontend
    echo $html;
    exit();
}
?>