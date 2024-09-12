<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("configdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["valorVenta"]) && !empty($post["stock"]) && !empty($post["idProducto"]) && !empty($post["idEntrega"])) {
            
            $bd = new Configdb();
            $conn = $bd->conexion();
        
            $sql = "INSERT INTO tbinventario ( InveValorVenta, InveStock, IdProductoFK, IdEnttregasFK ) 
                    VALUES (:VALORVENTA, :STOCK, :IDPRODUCTO, :IDENTREGA)";
        
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':VALORVENTA', $post["valorVenta"], PDO::PARAM_STR);
            $stmt->bindParam(':STOCK', $post["stock"], PDO::PARAM_INT);
            $stmt->bindParam(':IDPRODUCTO', $post["idProducto"], PDO::PARAM_INT);
            $stmt->bindParam(':IDENTREGA', $post["idEntrega"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => " registrado exitosamente"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Error al registrar "]);
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

        $sql = "SELECT t1.IdProductoFK as 'idProducto', 
                       t1.IdEnttregasFK as 'idEntrega', 
                       t1.InveValorVenta as 'valorVenta', 
                       t1.InveStock as 'stock', 
                       t2.ProvNombre as 'nombreProveedor', 
                       t3.EntreValorCosto as 'valorCosto'
                FROM tbinventario t1
                INNER JOIN tbproovedores t2 ON t1.IdProductoFK = t2.IdProveedorPK
                INNER JOIN tbentregas t3 ON t1.IdEnttregasFK = t3.IdEntregasPK";


        if (isset($_GET["id"])) {
            $sql .= " WHERE IdProductoPK = :id";
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

        if (!empty($post["idProducto"]) && !empty($post["idEntrega"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();
            $sql = "DELETE FROM tbinventario WHERE IdProductoFK = :idProducto AND IdEnttregasFK = :idEntrega";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':idProducto', $post["idProducto"], PDO::PARAM_INT);
            $stmt->bindValue(':idEntrega', $post["idEntrega"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => " eliminado con éxito"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Error al eliminar "]);
            }
            $stmt = null;
            $conn = null;
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['code' => 400, 'msg' => "ID del producto y entregas son requeridos"]);
        }
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', 'error' => $ex->getMessage()]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["valorVenta"]) && !empty($post["stock"]) && !empty($post["idProducto"]) && !empty($post["idEntrega"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();
        
            $sql = "UPDATE tbinventario   
                    SET InveValorVenta = :valorVenta, InveStock = :stock 
                    WHERE IdProductoFK = :idProducto AND IdEnttregasFK = :idEntrega";
        
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':valorVenta', $post["valorVenta"], PDO::PARAM_STR);
            $stmt->bindValue(':stock', $post["stock"], PDO::PARAM_INT);
            $stmt->bindValue(':idProducto', $post["idProducto"], PDO::PARAM_INT);
            $stmt->bindValue(':idEntrega', $post["idEntrega"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Producto actualizado con éxito"]);
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