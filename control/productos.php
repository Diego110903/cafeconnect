<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("configdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["nombre"]) && !empty($post["presentacion"]) && !empty($post["valorunitario"]) && !empty($post["minimostock"])) {
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

            // Consulta para insertar en tbproducto
            $sql = "INSERT INTO tbproducto (ProNombre, ProPresentacion, ProValorUnitario, ProMinimoStock) 
                    VALUES (:NOMBRE, :PRESENTACION, :VALORUNITARIO, :MINIMOSTOCK)";
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':NOMBRE', $post["nombre"], PDO::PARAM_STR);
            $stmt->bindValue(':PRESENTACION', $post["presentacion"], PDO::PARAM_STR);
            $stmt->bindValue(':VALORUNITARIO', $valorunitario, PDO::PARAM_STR);
            $stmt->bindValue(':MINIMOSTOCK', $post["minimostock"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Producto registrado exitosamente"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Error al registrar el producto"]);
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

        $sql = "SELECT p.IdProductoPK as 'id', 
        p.ProNombre as 'nombre', 
        p.ProPresentacion as 'presentacion', 
        p.ProValorUnitario as 'valorunitario', 
        p.ProMinimoStock as 'minimostock'
 FROM tbproducto p ";


        if (isset($_GET["id"])) {
            $sql .= " WHERE p.IdProductoPK = :id";
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

            $sql = "DELETE FROM tbproducto WHERE IdProductoPK = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $post["id"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Producto eliminado con éxito"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Error al eliminar el producto"]);
            }
            $stmt = null;
            $conn = null;
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['code' => 400, 'msg' => "ID del producto requerido"]);
        }
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', 'error' => $ex->getMessage()]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["id_producto"]) && !empty($post["nombre"]) && !empty($post["presentacion"]) && !empty($post["valorunitario"]) && !empty($post["minimostock"])) {
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

            // Consulta para actualizar en tbproducto
            $sql = "UPDATE tbproducto   
                    SET ProNombre = :nombre, ProPresentacion = :presentacion, ProValorUnitario = :valorunitario, ProMinimoStock = :minimostock
                    WHERE IdProductoPK = :id_producto";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id_producto', $post["id_producto"], PDO::PARAM_INT);
            $stmt->bindValue(':nombre', $post["nombre"], PDO::PARAM_STR);
            $stmt->bindValue(':presentacion', $post["presentacion"], PDO::PARAM_STR);
            $stmt->bindValue(':valorunitario', $valorunitario, PDO::PARAM_STR);
            $stmt->bindValue(':minimostock', $post["minimostock"], PDO::PARAM_INT);

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
}
?>










