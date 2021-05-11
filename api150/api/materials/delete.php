<?php 

    //region imports
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    //endregion

    // Conexión con la base de datos 
    include_once '../../config/database.php';
    include_once '../../util/commonFunctions.php';

    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();

    //region Definicion de los datos que llegan
    $data = json_decode(file_get_contents("php://input"));

    $id = $data->id_Medio;
    $token = $data->token;

    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 

        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            if (!empty($id)) {
                // Tengo todos los datos
                // Comprobamos que la id corresponde a un registro 
                if ($cf->comprobarExisteMedioPorId($id)) {
                    // Efectivamente existe y se puede eliminar
                    $query = "DELETE FROM medios WHERE id_Medio like ".$id.";";
                    $stmt = $database->getConn()->prepare($query);
                    $stmt->execute();
                    http_response_code(200);
                    echo json_encode(array(" status : 200, message : Elemento eliminado"));
                } else {
                    //No existe y por lo tanto no se puede eliminar 
                    http_response_code(406);
                    echo json_encode(array("status : 406, message : El registro no existe"));
                }
            } else {
                http_response_code(400);
                echo json_encode(" status : 400, message : faltan uno o más datos");
            }

        } else {
            http_response_code(401);
            echo json_encode("status : 401, message : Tiempo de sesión excedido");
        }

    } elseif ($cf->comprobarTokenAdmin($token) == 0) {
        http_response_code(403);
        echo json_encode("status : 401, message : no tiene permisos para realizar esta operación");
    } else {
        http_response_code(403);
        echo json_encode("status : 403, message : token no valido");
    }
    
    
    


?>