<?php

// Habilitar la visualización de errores para depuración
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once("configdb.php");

// Verificar que la solicitud es GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        // Conectar a la base de datos
        $bd = new Configdb();
        $conn = $bd->conexion();

        // Preparar y ejecutar la consulta
        $sql = "SELECT `IdMedioPagoPK`, `MedNombre` FROM `tbmediopago` ORDER BY `MedNombre` ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        // Obtener los resultados
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Enviar respuesta exitosa con datos
        header("Content-Type: application/json");
        header("HTTP/1.1 200 OK");
        echo json_encode(['code' => 200, 'data' => $result, 'msg' => "OK"]);

        // Cerrar la conexión y la declaración
        $stmt = null;
        $conn = null;
    } catch (Exception $ex) {
        // Manejo de excepciones y errores internos
        header("Content-Type: application/json");
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', "ERROR" => $ex->getMessage()]);
    }
} else {
    // Manejo de solicitudes no GET
    header("Content-Type: application/json");
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(['code' => 400, 'msg' => 'Error, la petición no se pudo procesar']);
}


?>