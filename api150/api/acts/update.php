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
 
     $idPrograma = htmlspecialchars($_GET["idPrograma"]);
     $nuevoTitulo = htmlspecialchars($_GET["nuevoTitulo"]);
     $nuevaFecha = htmlspecialchars($_GET["nuevaFecha"]);
     $boolEnUso = htmlspecialchars($_GET["enUso"]);
     // $nuevaCategoria = htmlspecialchars($_GET["nuevaCategoria"]); // La categoría se suprime para coincidir con las restricciones de la base de datos 
     $token = htmlspecialchars($_GET["token"]);
    //endregion
    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 

        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            // lo primero es comprobar que existe el elemento que se quiere modificar 
            if (!empty($idPrograma) && !empty($nuevoTitulo) && !empty($nuevaFecha) && $boolEnUso!=null) {
                // Tenemos todos los datos ok
                // Comprobamos que el id existe
                if ($cf->comprobarExisteActoPorId($idPrograma)) {

                    echo $logger->updated_element();
                    
                } else {
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