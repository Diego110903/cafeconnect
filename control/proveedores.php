<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


require_once("configdb.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["nit"]) && !empty($post["nombre"]) && !empty($post["apellidos"]) && !empty($post["email"]) &&
            !empty($post["celular"]) && !empty($post["ncuenta"]) && !empty($post["tipocuenta"]) && !empty($post["banco"])) {

            $bd = new Configdb();
            $conn = $bd->conexion();

            $sql = "INSERT INTO `tbproovedores`(`ProvNit`, `ProvNombre`, `ProvApellidos`, `ProvEmail`, `ProvCelular`, `ProvNcuenta`, `ProvTipoCuenta`, `IdBancoFK`)
                    VALUES (:NIT, :NOMBRE, :APELLIDOS, :EMAIL, :CELULAR, :NCUENTA, :TIPOCUENTA, :IDBANCO)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':NIT', $post["nit"], PDO::PARAM_STR);
            $stmt->bindValue(':NOMBRE', $post["nombre"], PDO::PARAM_STR);
            $stmt->bindValue(':APELLIDOS', $post["apellidos"], PDO::PARAM_STR);
            $stmt->bindValue(':EMAIL', $post["email"], PDO::PARAM_STR);
            $stmt->bindValue(':CELULAR', $post["celular"], PDO::PARAM_STR);
            $stmt->bindValue(':NCUENTA', $post["ncuenta"], PDO::PARAM_STR);
            $stmt->bindValue(':TIPOCUENTA', $post["tipocuenta"], PDO::PARAM_STR);
            $stmt->bindValue(':IDBANCO', $post["banco"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Proveedor registrado con éxito"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Error al registrar el proveedor"]);
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

        $sql = "SELECT t1.IdProveedorPK as 'id', 
                       T2.BanNombre as 'banco', 
                       t1.ProvNit as 'nit', 
                       t1.ProvNombre as 'nombre', 
                       t1.ProvApellidos as 'apellidos', 
                       t1.ProvEmail as 'email', 
                       t1.ProvCelular as 'celular', 
                       t1.ProvNcuenta as 'ncuenta', 
                       t1.ProvTipoCuenta as 'tipocuenta'
                FROM tbproovedores t1
                INNER JOIN tbbanco t2 ON t1.IdBancoFK = t2.IdBancoPK";

        if (isset($_GET["id"])) {
            $sql .= " WHERE t1.IdProveedorPK = :id";
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

            $sql = "DELETE FROM tbproovedores WHERE IdProveedorPK = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $post["id"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Proveedor eliminado con éxito"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => "Error al eliminar el proveedor"]);
            }
            $stmt = null;
            $conn = null;
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['code' => 400, 'msg' => "ID del proveedor requerido"]);
        }
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', 'error' => $ex->getMessage()]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        // Asegúrate de que todos los campos estén presentes
        if (!empty($post["id_proveedor"]) && !empty($post["nit"]) && !empty($post["nombre"]) &&
            !empty($post["apellidos"]) && !empty($post["email"]) && !empty($post["celular"]) &&
            !empty($post["ncuenta"]) && !empty($post["tipocuenta"]) && !empty($post["banco"])) {
            
            $bd = new Configdb();
            $conn = $bd->conexion();

            $sql = "UPDATE tbproovedores
                    SET ProvNit = :NIT, ProvNombre = :NOMBRE, ProvApellidos = :APELLIDOS, ProvEmail = :EMAIL, ProvCelular = :CELULAR, ProvNcuenta = :NCUENTA, ProvTipoCuenta = :TIPOCUENTA, IdBancoFK = :BANCO
                    WHERE IdProveedorPK = :PROVEEDOR";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':PROVEEDOR', $post["id_proveedor"], PDO::PARAM_INT);
            $stmt->bindValue(':NIT', $post["nit"], PDO::PARAM_STR);
            $stmt->bindValue(':NOMBRE', $post["nombre"], PDO::PARAM_STR);
            $stmt->bindValue(':APELLIDOS', $post["apellidos"], PDO::PARAM_STR);
            $stmt->bindValue(':EMAIL', $post["email"], PDO::PARAM_STR);
            $stmt->bindValue(':CELULAR', $post["celular"], PDO::PARAM_STR);
            $stmt->bindValue(':NCUENTA', $post["ncuenta"], PDO::PARAM_STR);
            $stmt->bindValue(':TIPOCUENTA', $post["tipocuenta"], PDO::PARAM_STR);
            $stmt->bindValue(':BANCO', $post["banco"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Proveedor actualizado con éxito"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                $errorInfo = $stmt->errorInfo();
                echo json_encode(['code' => 400, 'msg' => "Error al actualizar el proveedor", 'error' => $errorInfo]);
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
