<?php
// Habilitar la visualización de errores para depuración
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once("configdb.php");

header('Content-Type: application/json');

try {
    $bd = new Configdb();
    $conn = $bd->conexion();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $post = json_decode(file_get_contents('php://input'), true);
        if (!empty($post["fecha"]) && !empty($post["hora"]) && !empty($post["valorfactura"]) && !empty($post["mediopago"])) {
            $sql = "INSERT INTO `tbfacturas` (`FacFecha`, `FacHora`, `FacValorFactura`, `IdMedioPagoFK`) VALUES (:FECHA, :HORA, :VALORFACTURA, :IDMEDIOPAGO)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':FECHA', $post["fecha"], PDO::PARAM_STR);
            $stmt->bindValue(':HORA', $post["hora"], PDO::PARAM_STR);
            $stmt->bindValue(':VALORFACTURA', $post["valorfactura"], PDO::PARAM_STR);
            $stmt->bindValue(':IDMEDIOPAGO', $post["mediopago"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Factura registrada con éxito"]);
            } else {
                throw new Exception("Error al registrar la factura");
            }
        } else {
            throw new Exception("Datos incompletos");
        }
    } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
        $sql = "SELECT T1.IdFacturaPK as 'id', T1.FacFecha as 'fecha', T1.FacHora as 'hora', T1.FacValorFactura as 'valorfactura', T2.MedNombre as 'mediopago' FROM tbfacturas T1 INNER JOIN tbmediopago T2 ON T1.IdMedioPagoFK = T2.IdMedioPagoPK";
        if (isset($_GET["id"])) {
            $sql .= " WHERE T1.IdFacturaPK = :id";
        }
        $stmt = $conn->prepare($sql);
        if (isset($_GET["id"])) {
            $stmt->bindValue(':id', trim($_GET["id"]), PDO::PARAM_INT);
        }
        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode(['code' => 200, 'data' => $result, 'msg' => "OK"]);
        } else {
            throw new Exception("Error al procesar la solicitud");
        }
    } elseif ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        $post = json_decode(file_get_contents('php://input'), true);
        if (!empty($post["id"])) {
            $sql = "DELETE FROM tbfacturas WHERE IdFacturaPK = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $post["id"], PDO::PARAM_INT);
            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Factura eliminada con éxito"]);
            } else {
                throw new Exception("Error al eliminar la factura");
            }
        } else {
            throw new Exception("ID de la factura requerida");
        }
    } elseif ($_SERVER["REQUEST_METHOD"] == "PUT") {
        $post = json_decode(file_get_contents('php://input'), true);
        if (!empty($post["id_factura"]) && !empty($post["fecha"]) && !empty($post["hora"]) && !empty($post["valorfactura"]) && !empty($post["mediopago"])) {
            $sql = "UPDATE tbfacturas SET FacFecha = :FECHA, FacHora = :HORA, FacValorFactura = :VALORFACTURA, IdMedioPagoFK = :MEDIOPAGO WHERE IdFacturaPK = :FACTURA";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':FACTURA', $post["id_factura"], PDO::PARAM_INT);
            $stmt->bindValue(':FECHA', $post["fecha"], PDO::PARAM_STR);
            $stmt->bindValue(':HORA', $post["hora"], PDO::PARAM_STR);
            $stmt->bindValue(':VALORFACTURA', $post["valorfactura"], PDO::PARAM_STR);
            $stmt->bindValue(':MEDIOPAGO', $post["mediopago"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Factura actualizada con éxito"]);
            } else {
                throw new Exception("Error al actualizar la factura");
            }
        } else {
            throw new Exception("Datos incompletos");
        }
    } else {
        throw new Exception("Error, la petición no se pudo procesar");
    }
} catch (PDOException $ex) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', 'error' => $ex->getMessage()]);
} catch (Exception $ex) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(['code' => 400, 'msg' => $ex->getMessage()]);
} finally {
    $stmt = null;
    $conn = null;
}
?>






