<?php
require_once("configdb.php");

//header('Content-Type: application/json');
if($_SERVER["REQUEST_METHOD"]=="PUT"){
    try {
        $post = json_decode(file_get_contents('php://input'),true);
        
        if($post["iduser"]!="" && $post["token"]!=""){
            //echo $_post["user"]; 
            $bd = new Configdb();
            $conn = $bd->conexion();
            $sql = "UPDATE `token_acceso` SET `ESTADO`='INACTIVO' WHERE `ID_TOKEN`= ?";
            $stmt = $conn ->prepare($sql);
            $stmt->bindParam(1,$post["token"],PDO::PARAM_STR);
            if($stmt->execute()){
                header("HTTP/1.1 200 OK");
                echo json_encode(['code'=>200,'msg' => "OK"]);
            }else{
                header("HTTP/1.1 203 OK");
                echo json_encode(['code'=>203,'msg' => "Las credenciales no son validas"]);
            }
            $stmt = null;
            $conn = null;
        }
        //exit();
    } catch (Exception $ex) {
        header("HTTP/1.1 500");
        echo json_encode(['code'=>500,'msg' => 'Error interno al procesar su petici&oacute;n', "ERROR"=>$ex->getMessage()]);
    }
}else {
    header("HTTP/1.1 400");
    echo json_encode(['code'=>400,'msg' => 'Error, La peticion no se pudo procesar']);
}

?>
