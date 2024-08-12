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
}
?>








