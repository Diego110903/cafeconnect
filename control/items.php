<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("configdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["IdproductoFK"]) && !empty($post["IdFacturaFK"]) && !empty($post["ItemCantidad"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();

            // Obtener el valor unitario
            $sqlValor = "SELECT inVeValorUnitario FROM tbinventario WHERE id_producto = :idproducto";
            $stmtValor = $conn->prepare($sqlValor);
            $stmtValor->bindValue(':idproducto', $post["IdproductoFK"], PDO::PARAM_INT);
            $stmtValor->execute();
            $resultadoValor = $stmtValor->fetch(PDO::FETCH_ASSOC);
            $valorUnitario = $resultadoValor['inVeValorUnitario'];
            $itemValorTotal = $valorUnitario * $post["ItemCantidad"];

            $sql = "INSERT INTO tbitems (IdproductoFK, IdFacturaFK, ItemCantidad, ItemValorTotal) 
                    VALUES (:IDPRODUCTO, :IDFACTURA, :CANTIDAD, :VALORTOTAL)";
            $stmt = $conn->prepare($sql);
            
            $stmt->bindValue(':IDPRODUCTO', $post["IdproductoFK"], PDO::PARAM_INT);
            $stmt->bindValue(':IDFACTURA', $post["IdFacturaFK"], PDO::PARAM_INT);
            $stmt->bindValue(':CANTIDAD', $post["ItemCantidad"], PDO::PARAM_INT);
            $stmt->bindValue(':VALORTOTAL', $itemValorTotal, PDO::PARAM_STR);
            
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
        $sql = "SELECT 
        p.IdProductoPK AS 'producto', 
        f.IdFactura AS 'factura', 
        i.InveValorUnitario AS 'valorunitario', 
        t.ItemCantidad AS 'cantidad', 
        t.ItemValorTotal AS 'valortotal' 
    FROM tbitems t
    LEFT JOIN tbproducto p ON t.IdproductoFK = p.IdProductoPK
    LEFT JOIN tbfacturas f ON t.IdFacturaFK = f.IdFactura
    LEFT JOIN tbinventario i ON p.IdProductoPK = i.IdProductoFK";








        if (isset($_GET["idfactura"])) {
            $sql .= " WHERE t1.IdFacturaFK = :idfactura";
        }

        $stmt = $conn->prepare($sql);

        if (isset($_GET["idfactura"])) {
            $stmt->bindValue(':idfactura', trim($_GET["idfactura"]), PDO::PARAM_INT);
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
    } catch (Exception $ex) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(['code' => 400, 'msg' => $ex->getMessage()]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["id_item"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();

            $sql = "DELETE FROM tbitems WHERE id_item = :id_item";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id_item', $post["id_item"], PDO::PARAM_INT);

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
            echo json_encode(['code' => 400, 'msg' => "ID del item requerido"]);
        }
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', 'error' => $ex->getMessage()]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["id_item"]) && !empty($post["IdproductoFK"]) && !empty($post["ItemCantidad"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();

            $sql = "UPDATE tbitems   
                    SET IdproductoFK = :idproducto, ItemCantidad = :cantidad
                    WHERE id_item = :id_item";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id_item', $post["id_item"], PDO::PARAM_INT);
            $stmt->bindValue(':idproducto', $post["IdproductoFK"], PDO::PARAM_INT);
            $stmt->bindValue(':cantidad', $post["ItemCantidad"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "OK"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Error al actualizar el item"]);
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
}
?>












