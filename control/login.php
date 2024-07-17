<?php
// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("configdb.php");

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener el cuerpo de la solicitud y decodificarlo desde JSON
        $_POST = json_decode(file_get_contents('php://input'), true);

        if (!empty($_POST["user"]) && !empty($_POST["pass"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();
            $sql = "SELECT `IdUsuarioPK`, `UsuNombre`, `UsuApellidos` FROM `tbusuario` WHERE `UsuEmail`=:user AND `UsuContrasena`=:pass";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':user', $_POST["user"], PDO::PARAM_STR);
            $stmt->bindValue(':pass', $_POST["pass"], PDO::PARAM_STR);

            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    // Obtener el ID del usuario y el nombre completo
                    $idUser = $result[0]["IdUsuarioPK"];
                    $userNombre = $result[0]["UsuNombre"] . " " . $result[0]["UsuApellidos"];
                    
                    // Obtener el token
                    $idToken = $bd->obtenerToken($idUser, $userNombre);

                    // Enviar la respuesta JSON con el idToken
                    header("Content-Type: application/json");
                    echo json_encode([
                        'code' => 200,
                        'idUser' => $idUser,
                        'Usuario' => $userNombre,
                        'idToken' => $idToken,
                        'msg' => "ok"
                    ]);
                } else {
                    header("HTTP/1.1 203 Non-Authoritative Information");
                    header("Content-Type: application/json");
                    echo json_encode(['code' => 203, 'msg' => "Las credenciales no son válidas"]);
                }
            } else {
                header("HTTP/1.1 500 Internal Server Error");
                header("Content-Type: application/json");
                echo json_encode(['code' => 500, 'msg' => "Error al ejecutar la consulta"]);
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            header("Content-Type: application/json");
            echo json_encode(['code' => 400, 'msg' => 'Error, faltan parámetros']);
        }
    } else {
        header("HTTP/1.1 405 Method Not Allowed");
        header("Content-Type: application/json");
        echo json_encode(['code' => 405, 'msg' => 'Error, método no permitido']);
    }
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    header("Content-Type: application/json");
    echo json_encode([
        'code' => 500,
        'msg' => 'Error interno al procesar su petición',
        'error' => $e->getMessage()
    ]);
}

?>
































