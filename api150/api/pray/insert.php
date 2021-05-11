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
    $logger = new Logger();
    $dao = new Dao();

    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();

    //region Definicion de los datos que llegan
    $data = json_decode(file_get_contents("php://input"));

    $titulo = $data->titulo;
    $texto = $data->texto;
    $boolEnUso = $data->enUso;
    $token = $data->token;
    //endregion


    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 

        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            // comprobamos que no faltan datos vitales
            if (!empty($titulo) && !empty($texto) && $boolEnUso != null) {
                // Tenemos todos los datos
                //Comprobamos que el registro no existe ya en la base de datos 
                if ($cf->comprobarExisteOracionPorTitulo($titulo)) {
                    // la oración ya existe
                    echo $logger->already_exists("oración");
                } else {
                    // la oración no existe 
                    $dao->insertarOracion($titulo, $texto, $boolEnUso);
                    echo $logger->created_element();
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