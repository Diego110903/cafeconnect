<?php
require_once("configdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["nombre"]) && !empty($post["apellidos"]) && !empty($post["email"]) && !empty($post["rol"]) && !empty($post["password"]) && !empty($post["confirmar"])) {

            if ($post["password"] !== $post["confirmar"]) {
                throw new Exception("Las contraseñas no coinciden");
            }

            $bd = new Configdb();
            $conn = $bd->conexion();

            $sql = "INSERT INTO tbusuario (IdRolFK, UsuNombre, UsuApellidos, UsuEmail, UsuContrasena) 
                    VALUES (:ROL, :NOMBRE, :APELLIDOS, :EMAIL, :CONTRASENA)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':ROL', $post["rol"], PDO::PARAM_INT);
            $stmt->bindValue(':NOMBRE', $post["nombre"], PDO::PARAM_STR);
            $stmt->bindValue(':APELLIDOS', $post["apellidos"], PDO::PARAM_STR);
            $stmt->bindValue(':EMAIL', $post["email"], PDO::PARAM_STR);
            $stmt->bindValue(':CONTRASENA', password_hash($post["password"], PASSWORD_BCRYPT), PDO::PARAM_STR);

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
            
        $sql = "SELECT p.`IdProvedorPK`, p.`ProvIdentificacion`, p.`ProvNombre`, p.`ProvEmail`, p.`ProvCelular`, p.`ProvNcuenta`, p.`ProvTipoCuenta`, p.`IdBancoFK`, b.`BanNombre` FROM `tbproovedores` p INNER JOIN `tbbanco` b ON(p.`IdBancoFK`=`b`.`IdBancoPK`)";

        if (isset($_GET["id"])) {
            $sql = "SELECT p.`IdProvedorPK`, p.`ProvIdentificacion`, p.`ProvNombre`, p.`ProvEmail`, p.`ProvCelular`, p.`ProvNcuenta`, p.`ProvTipoCuenta`, p.`IdBancoFK`, b.`BanNombre` FROM `tbproovedores` p INNER JOIN `tbbanco` b ON(p.`IdBancoFK`=`b`.`IdBancoPK`) WHERE p.`IdProvedorPK` = :id";
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

            $sql = "DELETE FROM tbusuario WHERE IdUsuarioPK = :id";
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
            echo json_encode(['code' => 400, 'msg' => "ID del usuario requerido"]);
        }
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', "ERROR" => $ex->getMessage()]);
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