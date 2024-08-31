<?php
require_once("configdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if  (!empty($post["entregas"]) && !empty($post["proveedor"]) && !empty($post["valorcosto"]) && !empty($post["cantidad"]) && !empty($post["fn"])) {

           

            $bd = new Configdb();
            $conn = $bd->conexion();

            $sql = "INSERT INTO `tbentregas`(`IdEntregasPK`, `IdprovedorFK`, `EntreValorCosto`, `EntreCantidad`, `EntreFecha`) 
                    VALUES (:IDENTREGAS, :IDPROVEEDOR, :VALORCOSTO, :CANTIDAD, :FN)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':ENTREGAS', $post["entregas"], PDO::PARAM_INT);
            $stmt->bindValue(':PROVEEDOR', $post["proveedor"], PDO::PARAM_INT);
            $stmt->bindValue(':VALORCOSTO', $post["valorcosto"], PDO::PARAM_STR);
            $stmt->bindValue(':CANTIDAD', $post["cantidad"], PDO::PARAM_INT);
            $stmt->bindValue(':FN', $post["fn"], PDO::PARAM_STR);

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
            
        $sql = "SELECT T1.IdUsuarioPK as 'id', T2.RolNombre as 'rol', T1.UsuNombre as 'nombre', T1.UsuApellidos as 'apellidos', T1.UsuEmail as 'email' 
                FROM tbusuario T1 
                INNER JOIN tbrol T2 ON T1.IdRolFK = T2.IdRolPK";

        if (isset($_GET["id"])) {
            $sql = "SELECT T1.IdUsuarioPK as 'id', T2.RolNombre as 'rol', T1.UsuNombre as 'nombre', T1.UsuApellidos as 'apellidos', T1.UsuEmail as 'email', T1.IdUsuarioPK as 'idusu', T1.IdRolFK as 'idrol' 
                    FROM tbusuario T1 
                    INNER JOIN tbrol T2 ON T1.IdRolFK = T2.IdRolPK 
                    WHERE T1.IdUsuarioPK = :id";
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
} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["id_usuario"]) && !empty($post["nombre"]) && !empty($post["apellidos"]) && !empty($post["email"]) && !empty($post["rol"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();
            
            $sql = "UPDATE tbusuario 
                    SET IdRolFK = :ROL, UsuNombre = :NOMBRE, UsuApellidos = :APELLIDOS, UsuEmail = :EMAIL 
                    WHERE IdUsuarioPK = :USUARIO";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":USUARIO", $post["id_usuario"], PDO::PARAM_INT);
            $stmt->bindValue(":ROL", $post["rol"], PDO::PARAM_INT);
            $stmt->bindValue(":NOMBRE", $post["nombre"], PDO::PARAM_STR);
            $stmt->bindValue(":APELLIDOS", $post["apellidos"], PDO::PARAM_STR);
            $stmt->bindValue(":EMAIL", $post["email"], PDO::PARAM_STR);

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