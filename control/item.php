<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("configdb.php");

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    try {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post["id_item"])) {
            $bd = new Configdb();
            $conn = $bd->conexion();

            // Consulta para obtener información del producto relacionado con el ítem
            $sql = "SELECT p.ProValorUnitario, f.FacturaNumero, m.MediopagoNombre 
                    FROM tbitems i
                    JOIN tbproducto p ON i.IdproductoFK = p.IdProductoPK
                    JOIN tbfactura f ON i.IdFacturaFK = f.IdFacturaPK
                    JOIN tbmediopago m ON i.IdmediopagoFK = m.IdmediopagoPK
                    WHERE i.IditemPK = :id_item";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id_item', $post["id_item"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                $itemData = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($itemData) {
                    // Procedes a eliminar el ítem después de obtener la información
                    $sqlDelete = "DELETE FROM tbitems WHERE IditemPK = :id_item";
                    $stmtDelete = $conn->prepare($sqlDelete);
                    $stmtDelete->bindValue(':id_item', $post["id_item"], PDO::PARAM_INT);

                    if ($stmtDelete->execute()) {
                        header("HTTP/1.1 200 OK");
                        echo json_encode([
                            'code' => 200,
                            'msg' => "Ítem eliminado con éxito",
                            'data' => [
                                'valor_unitario' => $itemData['ProValorUnitario'],
                                'factura_numero' => $itemData['FacturaNumero'],
                                'medio_pago' => $itemData['MediopagoNombre']
                            ]
                        ]);
                    } else {
                        header("HTTP/1.1 400 Bad Request");
                        echo json_encode(['code' => 400, 'msg' => "Error al eliminar el ítem"]);
                    }
                } else {
                    header("HTTP/1.1 404 Not Found");
                    echo json_encode(['code' => 404, 'msg' => "Ítem no encontrado"]);
                }
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['code' => 400, 'msg' => 'Error al procesar la solicitud']);
            }

            $stmt = null;
            $conn = null;
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['code' => 400, 'msg' => "ID del ítem requerido"]);
        }
    } catch (PDOException $ex) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['code' => 500, 'msg' => 'Error interno al procesar su petición', 'error' => $ex->getMessage()]);
    }
}
?>

