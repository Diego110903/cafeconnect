<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("configdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["valorcosto"]) && !empty($post["cantidad"]) && !empty($post["fecha"]) && !empty($post["proveedor"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();

            $sql = "INSERT INTO tbentregas (EntreValorCosto, EntreCantidad, EntreFecha, IdproveedorFK) 
                    VALUES (:VALORCOSTO, :CANTIDAD, :FECHA, :IDPROVEEDOR)";
            $stmt = $conn->prepare($sql);
            
            $stmt->bindValue(':VALORCOSTO', $post["valorcosto"], PDO::PARAM_STR);
            $stmt->bindValue(':CANTIDAD', $post["cantidad"], PDO::PARAM_INT);
            $stmt->bindValue(':FECHA', $post["fecha"], PDO::PARAM_STR);
            $stmt->bindValue(':IDPROVEEDOR', $post["proveedor"], PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "OK"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Inconvenientes al gestionar la consulta"]);
            }
            $stmt = null;
            $conn = null;
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['code' => 400, 'msg' => "Datos incompletos"]);
        }
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', "ERROR" => $ex->getMessage()]);
    } catch (Exception $ex) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(['code' => 400, 'msg' => $ex->getMessage()]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        $bd = new Configdb();
        $conn = $bd->conexion();

        $sql = "SELECT t1.IdEntregasPK as 'id', 
                       t2.ProvNombre as 'nombre', 
                       t1.EntreValorCosto as 'valorcosto', 
                       t1.EntreCantidad as 'cantidad', 
                       t1.EntreFecha as 'fecha' 
                FROM tbentregas t1 
                INNER JOIN tbproovedores t2 ON t1.IdproveedorFK = t2.IdproveedorPK";

        if (isset($_GET["id"])) {
            $sql .= " WHERE t1.IdEntregasPK = :id";
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

            $sql = "DELETE FROM tbentregas WHERE IdEntregasPK = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $post["id"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Entrega eliminada con éxito"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Error al eliminar la entrega"]);
            }
            $stmt = null;
            $conn = null;
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['code' => 400, 'msg' => "ID de la entrega requerido"]);
        }
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', 'error' => $ex->getMessage()]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["id_entregas"]) && !empty($post["valorcosto"]) && !empty($post["cantidad"]) && !empty($post["fecha"]) && !empty($post["proveedor"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();

            $sql = "UPDATE tbentregas   
                    SET EntreValorCosto = :valorcosto, EntreCantidad = :cantidad, EntreFecha = :fecha, IdproveedorFK = :proveedor 
                    WHERE IdEntregasPK = :id_entregas";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id_entregas', $post["id_entregas"], PDO::PARAM_INT);
            $stmt->bindValue(':valorcosto', $post["valorcosto"], PDO::PARAM_STR);
            $stmt->bindValue(':cantidad', $post["cantidad"], PDO::PARAM_INT);
            $stmt->bindValue(':fecha', $post["fecha"], PDO::PARAM_STR);
            $stmt->bindValue(':proveedor', $post["proveedor"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "OK"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Inconvenientes al gestionar la consulta"]);
            }
            $stmt = null;
            $conn = null;
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['code' => 400, 'msg' => "Datos incompletos"]);
        }
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', "ERROR" => $ex->getMessage()]);
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(['code' => 400, 'msg' => 'Error, la petición no se pudo procesar']);
}
?>














