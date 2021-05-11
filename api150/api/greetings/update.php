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
    $logger = new Logger();
    $dao = new Dao();
     //region Definicion de los datos que llegan
     $data = json_decode(file_get_contents("php://input"));
 
     $idSaludo = htmlspecialchars($_GET["idSaludo"]);
     $nuevoTitulo = htmlspecialchars($_GET["nuevoTitulo"]);
     $nuevaDescripcion = htmlspecialchars($_GET["nuevaDescripcion"]);
     $nuevoTexto = htmlspecialchars($_GET["nuevoTexto"]);
     $boolEnuso = htmlspecialchars($_GET["enUso"]);

     
     //endregion
     $token = htmlspecialchars($_GET["token"]);

    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 

        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            if (!empty($idSaludo) && !empty($nuevoTitulo) && !empty($nuevaDescripcion) && !empty($nuevoTexto)) {
                // Tenemos todos los datos ok
                // Comprobamos que el id existe
                if ($cf->comprobarExisteSaludoPorId($idSaludo)) {
                    $dao->actualizarSaludo($nuevoTitulo, $nuevaDescripcion, $nuevoTexto, $boolEnuso, $idSaludo);
                    http_response_code(200);
                    echo $logger->updated_element();
                } else {
                    http_response_code(406);
                    echo $logger->not_exists("saludo");
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

     // lo primero es comprobar que existe el elemento que se quiere modificar 
     
?>