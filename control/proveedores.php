<?php
require_once("configdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["proveedor"]) && !empty($post["nit"]) && !empty($post["nombre"]) && 
            !empty($post["apellidos"]) && !empty($post["email"]) && !empty($post["celular"]) && 
            !empty($post["ncuenta"]) && !empty($post["tipocuenta"]) && !empty($post["banco"])) {

            $bd = new Configdb();
            $conn = $bd->conexion();

            $sql = "INSERT INTO tbproveedores (`IdProvedorPK`, `ProvNit`, `ProvNombre`, `ProvApellidos`, `ProvEmail`, `ProvCelular`, `ProvNcuenta`, `ProvTipoCuenta`, `IdBancoFK`)
                    VALUES (:PROVEEDOR, :NIT, :NOMBRE, :APELLIDOS, :EMAIL, :CELULAR, :NCUENTA, :TIPOCUENTA, :IDBANCO)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':PROVEEDOR', $post["proveedor"], PDO::PARAM_INT);
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
            
        $sql = "SELECT p.`IdProvedorPK`, p.`ProvNit`, p.`ProvNombre`, p.`ProvEmail`, p.`ProvCelular`, p.`ProvNcuenta`, p.`ProvTipoCuenta`, p.`IdBancoFK`, b.`BanNombre` 
                FROM `tbproovedores` p 
                INNER JOIN `tbbanco` b ON(p.`IdBancoFK`=`b`.`IdBancoPK`)";

        if (isset($_GET["id"])) {
            $sql = "SELECT p.`IdProvedorPK`, p.`ProvNit`, p.`ProvNombre`, p.`ProvEmail`, p.`ProvCelular`, p.`ProvNcuenta`, p.`ProvTipoCuenta`, p.`IdBancoFK`, b.`BanNombre` 
                    FROM `tbproovedores` p 
                    INNER JOIN `tbbanco` b ON(p.`IdBancoFK`=`b`.`IdBancoPK`) 
                    WHERE p.`IdProvedorPK` = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', trim($_GET["id"]), PDO::PARAM_INT);
        } else {
            $stmt = $conn->prepare($sql);
        }

        if ($stmt->execute()) {                
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode(['code' => 200, 'data' => $result, 'msg' => "OK"]); 
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['code' => 400, 'msg' => 'Error, La petición no se pudo procesar']);
        }
        $stmt = null;
        $conn = null;
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', "ERROR" => $ex->getMessage()]);
    }

} else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);
        
        if (!empty($post["id"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();

            $sql = "DELETE FROM tbproovedores WHERE IdProvedorPK = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $post["id"], PDO::PARAM_INT);

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
            echo json_encode(['code' => 400, 'msg' => "ID del proveedor requerido"]);
        }
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', "ERROR" => $ex->getMessage()]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["proveedor"]) && !empty($post["nit"]) && !empty($post["nombre"]) && 
            !empty($post["apellidos"]) && !empty($post["email"]) && !empty($post["celular"]) && 
            !empty($post["ncuenta"]) && !empty($post["tipocuenta"]) && !empty($post["banco"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();
            
            $sql = "UPDATE tbproovedores 
                    SET IdBancoFK = :BANCO, ProvNit = :NIT, ProvNombre = :NOMBRE, ProvApellidos = :APELLIDOS, ProvEmail = :EMAIL, ProvCelular = :CELULAR, ProvNcuenta = :NCUENTA, ProvTipoCuenta = :TIPOCUENTA
                    WHERE IdProvedorPK = :PROVEEDOR";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':PROVEEDOR', $post["proveedor"], PDO::PARAM_INT);
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
    echo json_encode(['code' => 400, 'msg' => 'Error, La petición no se pudo procesar']);
}


?>