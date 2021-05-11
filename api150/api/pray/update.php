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

$idOracion = htmlspecialchars($_GET["idOracion"]);
$nuevoTitulo = htmlspecialchars($_GET["nuevoTitulo"]);
$nuevoTexto = htmlspecialchars($_GET["nuevoTexto"]);
$boolEnUso = htmlspecialchars($_GET["enUso"]);
//endregion

$token = htmlspecialchars($_GET["token"]);

    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 
        // lo primero es comprobar que existe el elemento que se quiere modificar 

        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            if (!empty($idOracion) && !empty($nuevoTitulo) && !empty($nuevoTexto) && $boolEnUso != null) {
                // Tenemos todos los datos ok
                // Comprobamos que el id existe
                if ($cf->comprobarExisteOracionPorId($idOracion)) {
                    $dao->actualizarOracion($nuevoTitulo, $nuevoTexto, $boolEnUso, $idOracion);
                    echo $logger->updated_element();
                } else {
                    echo $logger->not_exists("oración");
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