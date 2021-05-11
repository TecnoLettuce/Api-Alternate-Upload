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

    $titulo = $data->titulo;
    $descripcion = $data->descripcion;
    $texto = $data->texto;
    $boolEnUso = $data->enUso;
    $token = $data->token;
    //endregion

    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 

        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            if (!empty($titulo) && !empty($descripcion) && !empty($texto) && $boolEnUso != null) {
                // Tenemos todos los datos
                //Comprobamos que el registro no existe ya en la base de datos 
                if ($cf->comprobarExisteSaludoPorTitulo($titulo)) {
                    // El Saludo ya existe
                    http_response_code(406);
                    echo $logger->already_exists("saludo");
                } else {
                    // El saludo no existe 
                    $dao->insertarSaludo($titulo, $descripcion, $texto, $boolEnUso);
                    http_response_code(201);
                    echo $logger->created_element();
                }
            } else {
                http_response_code(400);
                echo $logger->incomplete_data();
            }

        } else {
            http_response_code(401);
            echo $logger->expired_session();
        }

    } elseif ($cf->comprobarTokenAdmin($token) == 0) {
        http_response_code(403);
        echo $logger->not_permission();
    } else {
        http_response_code(403);
        echo $logger->invalid_token();
    }

    

    


?>