<?php
require_once("config.class.php")
//header('content-type: application/json');
if($_SERVER["REQUEST_METHOD"]=="POST"){
    try {
        $_post = json_decode(file_get_contents('php://input'),true);
        //var_dump($_post);
        if($_post["user"]!="" && $_post["pass"]!=""){
        //      echo $_post["user"];
                $bd = new Config();
                $conn = $bd->conexion();
                $sql ="INSERT INTO tbusuario(IdUsuarioPK, IdRolFK, UsuNombre, UsuEmail, UsuContraseña) VALUES ()";
                $stmt = $conn ->prepare($sql);
                if($stmt->execute()){
                echo "SQL realizada correctamente";
                }else{
                echo "SQL no aplicada";
                }




            header("HTTP/1.1 200");
            echo json_encode(['code'=>200,'msg' => 'Consulta finalizada correctamente']);
        //     http_response_code(200);    
        }
        exit();
    } catch (Exception $ex) {
        header("HTTP/1.1 500");
        echo json_encode(['code'=>500,'msg' => 'Error, '.$ex->getMessage()]);
    }  
}else {
    header("HTTP/1.1 400");
    echo json_encode(['code'=>400, 'msg' => 'Error, la peticion no se pudo procesar']);    
}               

?>



