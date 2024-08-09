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
            $stmt->bindValue(':CONTRASENA', md5($post["password"]), PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("HTTP/1.1 200 OK");
                echo json_encode(['code' => 200, 'msg' => "Usuario registrado correctamente"]);
            } else {
                throw new Exception("No se pudo ejecutar la consulta");
            }
        } else {
            throw new Exception("Faltan campos por completar");
        }

    } catch (Exception $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => $ex->getMessage()]);
    }
}
?>

?>





