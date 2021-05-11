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
    include_once '../../objects/DAO.php';
    include_once '../../util/logger.php';

    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();
    $dao = new Dao();
    $logger = new Logger();

    //region Definicion de los datos que llegan
    $data = json_decode(file_get_contents("php://input"));

    $idRecibida = $data->id;
    $token = $data->token;

    // Comprobamos que el token es de admin
    if ($cf->comprobarTokenAdmin($token) == 1) {
        // Token de admin

        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            if (!empty($idRecibida)) {
                // Tengo todos los datos
                // Comprobamos que la id corresponde a un registro 
                if ($cf->comprobarExisteActoPorId($idRecibida)) {
                    // Efectivamente existe y se puede eliminar
                    $dao->borrarActo($idRecibida);

                    echo $logger->deleted_element();
                } else {
                    //No existe y por lo tanto no se puede eliminar 
                    echo $logger->not_exists("acto");
                }
            } else {
                echo $logger->incomplete_data();
            }

        } else {
            echo $logger->expired_session();
        }

    } elseif ($cf->comprobarTokenAdmin($token) == 0) {
        echo $logger->not_permission();
    } else {
        echo $logger->invalid_token();
    }
    

?>