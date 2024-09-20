<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("configdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["id_producto"]) && !empty($post["id_entregas"]) && !empty($post["stock"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();

            // Consulta para obtener el valor unitario de tbproducto
            $sqlProducto = "SELECT ProdValorUnitario FROM tbproducto WHERE IdProducto = :id_producto";
            $stmtProducto = $conn->prepare($sqlProducto);
            $stmtProducto->bindValue(':id_producto', $post["id_producto"], PDO::PARAM_INT);
            $stmtProducto->execute();
            $producto = $stmtProducto->fetch(PDO::FETCH_ASSOC);

            if ($producto && isset($producto["ProdValorUnitario"])) {
                $valorunitario = $producto["ProdValorUnitario"]; // Tomamos el valor unitario de tbproducto

                // Inserción en la tabla tbinventario
                $sql = "INSERT INTO tbinventario (IdProductoFK, IdEntregasFK, InveValorUnitario, InveStock) 
                        VALUES (:id_producto, :id_entregas, :valorunitario, :stock)";
                $stmt = $conn->prepare($sql);

                $stmt->bindValue(':id_producto', $post["id_producto"], PDO::PARAM_INT);
                $stmt->bindValue(':id_entregas', $post["id_entregas"], PDO::PARAM_INT);
                $stmt->bindValue(':valorunitario', $valorunitario, PDO::PARAM_STR); // Aquí usamos el valor unitario de tbproducto
                $stmt->bindValue(':stock', $post["stock"], PDO::PARAM_INT);

                if ($stmt->execute()) {
                    header("HTTP/1.1 200 OK");
                    echo json_encode(['code' => 200, 'msg' => "Inventario registrado exitosamente"]);
                } else {
                    header("HTTP/1.1 400 Bad Request");
                    echo json_encode(['code' => 400, 'msg' => "Error al registrar el inventario"]);
                }
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Producto no encontrado o sin valor unitario"]);
            }

            $stmtProducto = null;
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

        // Consulta para seleccionar los valores de tbinventario y tbproducto
        $sql = "SELECT tbinventario.IdInventario, tbinventario.InveStock, tbinventario.InveValorUnitario, 
                       tbproducto.ProdNombre, tbproducto.ProdValorUnitario 
                FROM tbinventario 
                JOIN tbproducto ON tbinventario.IdProductoFK = tbproducto.IdProducto";

        if (isset($_GET["id"])) {
            $sql .= " WHERE tbinventario.IdInventario = :id";
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
}
?>



