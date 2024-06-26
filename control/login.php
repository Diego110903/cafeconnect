<?php
// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("configdb.php");

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $_post = json_decode(file_get_contents('php://input'), true);

        if (!empty($_post["user"]) && !empty($_post["pass"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();
            $sql = "SELECT `IdUsuarioPK`, `UsuNombre`, `UsuApellidos` FROM `tbusuario` WHERE `UsuEmail`=:user AND `UsuContrasena`=:pass";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user', $_post["user"]);
            $stmt->bindParam(':pass', $_post["pass"]);

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    header("HTTP/1.1 200 OK");
                    echo json_encode([
                        'code' => 200,
                        'idUser' => $result[0]["IdUsuarioPK"],
                        'Usuario' => $result[0]["UsuNombre"] . " " . $result[0]["UsuApellidos"],
                        'msg' => "ok"
                    ]);
                } else {
                    header("HTTP/1.1 203 Non-Authoritative Information");
                    echo json_encode(['code' => 203, 'msg' => "Las credenciales no son válidas"]);
                }
            } else {
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode(['code' => 500, 'msg' => "Error al ejecutar la consulta"]);
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['code' => 400, 'msg' => 'Error, faltan parámetros']);
        }
    } else {
        header("HTTP/1.1 405 Method Not Allowed");
        echo json_encode(['code' => 405, 'msg' => 'Error, método no permitido']);
    }
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode([
        'code' => 500,
        'msg' => 'Error interno al procesar su petición',
        'error' => $e->getMessage()
    ]);
}
?>














