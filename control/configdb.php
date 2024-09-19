<?php

class Configdb {
    private $db_host = "localhost"; // Lugar o IP donde está el servidor de Base de datos
    private $db_port = "3306"; // Puerto de Base de datos MySQL
    private $db_user = "dbDiego"; // dbDiego Nombre del Usuario para conectarnos a la Base de Datos
    private $db_pass = "cafDB1109"; // cafDB1109 Contraseña del Usuario de la Base de datos
    private $db_name = "bd cafeconnect"; // Nombre de la Base de Datos
    
    public function conexion() {
        $link = "mysql:host=".$this->db_host.":".$this->db_port.";dbname=".$this->db_name.";";
        try {
            $conn = new PDO($link, $this->db_user, $this->db_pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            throw new Exception("ERROR: ".$e->getMessage());
        }
    }
    

    public function obtenerToken($iduser, $nombreuser) {
        if (!is_int($iduser) || empty($nombreuser)) {
            throw new Exception("Datos de entrada no válidos.");
        }

        $estado = "ACTIVO";
        $token = bin2hex(openssl_random_pseudo_bytes(16, $crypto_strong));

        if (!$crypto_strong) {
            throw new Exception("La generación del token no es criptográficamente segura.");
        }

        try {
            $conn = $this->conexion();
            $sql = "INSERT INTO token_acceso (ID_TOKEN, ID_USUARIO_FK, USUARIO, FECHA_REG, HORA_REG, ESTADO) VALUES (:token, :iduser, :nombreuser, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, :estado)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':iduser', $iduser, PDO::PARAM_INT);
            $stmt->bindParam(':nombreuser', $nombreuser, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return $token;
            } else {
                throw new Exception("No se pudo insertar el token en la base de datos.");
            }
        } catch (PDOException $e) {
            throw new Exception("ERROR: " . $e->getMessage());
        }
    }
}
?>


