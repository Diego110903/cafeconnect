<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("configdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["id_producto"]) && !empty($post["id_factura"]) && 
            !empty($post["cantidad"]) && !empty($post["valortotal"])) {
            
            $bd = new Configdb();
            $conn = $bd->conexion();

            
            $sql = "INSERT INTO tbitems (IdproductoFK, IdFacturaFK, ItemCantidad, ItemValorTotal) 
                    VALUES (:id_producto, :id_factura, :cantidad, :valortotal)";
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':id_producto', $post["id_producto"], PDO::PARAM_INT);
            $stmt->bindValue(':id_factura', $post["id_factura"], PDO::PARAM_INT);
            $stmt->bindValue(':cantidad', $post["cantidad"], PDO::PARAM_INT);
            $stmt->bindValue(':valortotal', $post["valortotal"], PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Item registrado exitosamente"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Error al registrar el item"]);
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
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        $bd = new Configdb();
        $conn = $bd->conexion();

        $sql = "SELECT 
                    i.IdproductoFK as 'id_producto', 
                    i.IdFacturaFK as 'id_factura', 
                    i.ItemCantidad as 'cantidad', 
                    i.ItemValorTotal as 'valortotal', 
                    iv.InveValorUnitario as 'valorunitario' 
                FROM tbitems i 
                LEFT JOIN tbinventario iv ON i.IdproductoFK = iv.IdProductoFK";

        if (isset($_GET["id_producto"]) && isset($_GET["id_factura"])) {
            $sql .= " WHERE i.IdproductoFK = :id_producto AND i.IdFacturaFK = :id_factura";
        }

        $stmt = $conn->prepare($sql);

        if (isset($_GET["id_producto"]) && isset($_GET["id_factura"])) {
            $stmt->bindValue(':id_producto', trim($_GET["id_producto"]), PDO::PARAM_INT);
            $stmt->bindValue(':id_factura', trim($_GET["id_factura"]), PDO::PARAM_INT);
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

        if (!empty($post["id_producto"]) && !empty($post["id_factura"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();

           
            $sql = "DELETE FROM tbitems WHERE IdproductoFK = :id_producto AND IdFacturaFK = :id_factura";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id_producto', $post["id_producto"], PDO::PARAM_INT);
            $stmt->bindValue(':id_factura', $post["id_factura"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Item eliminado con éxito"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Error al eliminar el item"]);
            }
            $stmt = null;
            $conn = null;
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['code' => 400, 'msg' => "ID del producto y factura requeridos"]);
        }
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', 'error' => $ex->getMessage()]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["id_producto"]) && !empty($post["id_factura"]) && 
            !empty($post["cantidad"]) && !empty($post["valortotal"])) {
            
            $bd = new Configdb();
            $conn = $bd->conexion();

            
            $sql = "UPDATE tbitems   
                    SET ItemCantidad = :cantidad, 
                        ItemValorTotal = :valortotal 
                    WHERE IdproductoFK = :id_producto AND IdFacturaFK = :id_factura";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id_producto', $post["id_producto"], PDO::PARAM_INT);
            $stmt->bindValue(':id_factura', $post["id_factura"], PDO::PARAM_INT);
            $stmt->bindValue(':cantidad', $post["cantidad"], PDO::PARAM_INT);
            $stmt->bindValue(':valortotal', $post["valortotal"], PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Item actualizado con éxito"]);
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
}
?>



















