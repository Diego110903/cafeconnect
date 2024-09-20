<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("configdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["id_producto"]) && !empty($post["id_entregas"]) && !empty($post["valorunitario"]) && !empty($post["stock"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();

            // Ahora tomas el valor unitario directamente del POST
            $valorunitario = $post["valorunitario"];

            // Verificar que el valor unitario no sea nulo
            if (is_null($valorunitario)) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Valor unitario no disponible"]);
                return;
            }

            // Consulta para insertar en tbinventario
            $sql = "INSERT INTO tbinventario (IdProductoFK, IdEnttregasFK, InveValorUnitario, InveStock) 
                    VALUES (:id_producto, :id_entregas, :valorunitario, :stock)";
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':id_producto', $post["id_producto"], PDO::PARAM_INT);
            $stmt->bindValue(':id_entregas', $post["id_entregas"], PDO::PARAM_INT);
            $stmt->bindValue(':valorunitario', $valorunitario, PDO::PARAM_STR);
            $stmt->bindValue(':stock', $post["stock"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Inventario registrado exitosamente"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Error al registrar el inventario"]);
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

        $sql = "SELECT IdInventario as 'id', IdProductoFK as 'id_producto', IdEnttregasFK as 'id_entregas', InveValorUnitario as 'valorunitario', InveStock as 'stock' 
                FROM tbinventario";

        if (isset($_GET["id"])) {
            $sql .= " WHERE IdInventario = :id";
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

            $sql = "DELETE FROM tbinventario WHERE IdInventario = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $post["id"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Inventario eliminado con éxito"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Error al eliminar el inventario"]);
            }
            $stmt = null;
            $conn = null;
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['code' => 400, 'msg' => "ID del inventario requerido"]);
        }
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', 'error' => $ex->getMessage()]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["id"]) && !empty($post["id_producto"]) && !empty($post["id_entregas"]) && !empty($post["valorunitario"]) && !empty($post["stock"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();

            // Ahora tomas el valor unitario directamente del POST
            $valorunitario = $post["valorunitario"];

            // Verificar que el valor unitario no sea nulo
            if (is_null($valorunitario)) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Valor unitario no disponible"]);
                return;
            }

            // Consulta para actualizar en tbinventario
            $sql = "UPDATE tbinventario   
                    SET IdProductoFK = :id_producto, IdEnttregasFK = :id_entregas, InveValorUnitario = :valorunitario, InveStock = :stock
                    WHERE IdInventario = :id";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $post["id"], PDO::PARAM_INT);
            $stmt->bindValue(':id_producto', $post["id_producto"], PDO::PARAM_INT);
            $stmt->bindValue(':id_entregas', $post["id_entregas"], PDO::PARAM_INT);
            $stmt->bindValue(':valorunitario', $valorunitario, PDO::PARAM_STR);
            $stmt->bindValue(':stock', $post["stock"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Inventario actualizado con éxito"]);
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
