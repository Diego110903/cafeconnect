<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


require_once("configdb.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["fecha"]) && !empty($post["hora"]) && !empty($post["valorfactura"]) && !empty($post["mediopago"])) {

            $bd = new Configdb();
            $conn = $bd->conexion();

            $sql = "INSERT INTO `tbfacturas`(`FacFecha`, `FacHora`, `FacValorFactura`, `FacMedioPago";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':FECHA', $post["fecha"], PDO::PARAM_STR);
            $stmt->bindValue(':HORA', $post["hora"], PDO::PARAM_STR);
            $stmt->bindValue(':VALORFACTURA', $post["valorfactura"], PDO::PARAM_STR);
            $stmt->bindValue(':IDMEDIOPAGO', $post["medioapago"], PDO::PARAM_INT); 

            

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Facturas registradas con éxito"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Error al registrar las Facturas"]);
            }
            $stmt = null;
            $conn = null;
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['code' => 400, 'msg' => "Datos incompletos"]);
        }
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', 'error' => $ex->getMessage()]);
    } catch (Exception $ex) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(['code' => 400, 'msg' => $ex->getMessage()]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        $bd = new Configdb();
        $conn = $bd->conexion();

        $sql = "SELECT t1.IdFacturaPK as 'id', 
                       t1.FacFecha as 'fecha', 
                       t1.FacHora as 'hora', 
                       t1.FacValorFactura as 'valor_factura', 
                       t2.MedioPagoNombre as 'medio_pago'
                FROM tbfacturas t1
                INNER JOIN tbmediopago t2 ON t1.IdMedioPagoFK = t2.IdMedioPagoPK";


        if (isset($_GET["id"])) {
            $sql .= " WHERE t1.IdFcaturaPK = :id";
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
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['code' => 400, 'msg' => 'Error al procesar la solicitud']);
        }
        
        $stmt = null;
        $conn = null;
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', 'error' => $ex->getMessage()]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["id"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();

            $sql = "DELETE FROM tbfacturas WHERE IdFacturasPK = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $post["id"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Factura eliminada con éxito"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Error al eliminar la factura"]);
            }
            $stmt = null;
            $conn = null;
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['code' => 400, 'msg' => "ID de la factura requerida"]);
        }
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', 'error' => $ex->getMessage()]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        // Asegúrate de que todos los campos estén presentes
        if (!empty($post["id_factura"]) && !empty($post["fecha"]) && !empty($post["hora"]) &&
            !empty($post["valorfactura"]) && !empty($post["mediopago"])) {
            
            $bd = new Configdb();
            $conn = $bd->conexion();

            $sql = "UPDATE tbfacturas
                    SET FacFecha = :FACTURA, FacHora = :HORA, FacValorFactura = :VALORFACTURA, mediopago = :IDMEDIOPAGO
                    WHERE IdFacturaPK = :FACTURA";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':FACTURA', $post["id_factura"], PDO::PARAM_INT); 
            $stmt->bindValue(':FECHA', $post["fecha"], PDO::PARAM_STR); 
            $stmt->bindValue(':HORA', $post["hora"], PDO::PARAM_STR);
            $stmt->bindValue(':VALORFACTURA', $post["valorfactura"], PDO::PARAM_STR);
            $stmt->bindValue(':MEDIOPAGO', $post["mediopago"], PDO::PARAM_INT); 
            
 

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Fcatura actualizada con éxito"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                $errorInfo = $stmt->errorInfo();
                echo json_encode(['code' => 400, 'msg' => "Error al actualizar la factura", 'error' => $errorInfo]);
            }
            $stmt = null;
            $conn = null;
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['code' => 400, 'msg' => "Datos incompletos"]);
        }
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', 'error' => $ex->getMessage()]);
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(['code' => 400, 'msg' => 'Error, La petición no se pudo procesar']);
}

?>
