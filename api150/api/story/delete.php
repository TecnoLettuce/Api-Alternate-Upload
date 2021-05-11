<?php 

    //region imports
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Access-Control-Allow-Headers, Authorization, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD');
    header("Access-Control-Max-Age: 3600");
    //endregion

    /* cosa de la que no me fio */
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        // Indica los métodos permitidos.
        header('Access-Control-Allow-Methods: GET, POST, DELETE');
        // Indica los encabezados permitidos.
        header('Access-Control-Allow-Headers: Authorization');
        http_response_code(204);
    }

    // Conexión con la base de datos 
    include_once '../../config/database.php';
    include_once '../../util/commonFunctions.php';

    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();

    //region Definicion de los datos que llegan
    $data = json_decode(file_get_contents("php://input"));

    $idRecibida = $data->idHistoria;
    $token = $data->token;

    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 
        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            if (!empty($idRecibida)) {
                // Tengo todos los datos
                // Comprobamos que la id corresponde a un registro 
                if ($cf->comprobarExisteHistoriaPorId($idRecibida)) {
                    // Efectivamente existe y se puede eliminar
                    $query = "DELETE FROM historias WHERE id_Historia like ".$idRecibida.";";
                    $stmt = $database->getConn()->prepare($query);
                    $stmt->execute();
                    echo json_encode(array(" status : 200, message : Elemento eliminado"));
                } else {
                    //No existe y por lo tanto no se puede eliminar 
                    echo json_encode(array("status : 406, message : El registro no existe"));
                }
            } else {
                echo json_encode("status : 400, message : faltan uno o más datos");
            }

        } else {
            echo json_encode("status : 401, message : Tiempo de sesión excedido");
        }

    } elseif ($cf->comprobarTokenAdmin($token) == 0) {
        echo json_encode("status : 401, message : no tiene permisos para realizar esta operación");
    } else {
        echo json_encode("status : 403, message : token no valido");
    }
    
    
    


?>