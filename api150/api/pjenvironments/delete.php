<?php 

    //region imports
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Access-Control-Allow-Headers, Authorization, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD');
    header("Access-Control-Max-Age: 3600");
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
    $logger = new Logger();
    $dao = new Dao();

    //region Definicion de los datos que llegan
    $data = json_decode(file_get_contents("php://input"));

    $idRecibida = $data->idAmbiente;
    $token = $data->token;

    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 
        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            if (!empty($idRecibida)) {
                // Tengo todos los datos
                // Comprobamos que la id corresponde a un registro 
                if ($cf->comprobarExisteAmbientePorId($idRecibida)) {
                    // Efectivamente existe y se puede eliminar
                    $dao->borrarAmbiente($idRecibida);
                    echo $logger->deleted_element();
                } else {
                    //No existe y por lo tanto no se puede eliminar 
                    echo $logger->not_exists("ambiente");
                }
            } else {
                echo $logger->incomplete_data();
            }

        } else {
            echo $logger->incomplete_data();
        }

    } elseif ($cf->comprobarTokenAdmin($token) == 0) {
        echo $logger->not_permission();
    } else {
        echo $logger->invalid_token();
    }
    
?>