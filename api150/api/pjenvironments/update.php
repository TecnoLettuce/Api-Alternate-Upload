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

$idAmbiente = htmlspecialchars($_GET["idAmbiente"]);
$nuevoTitulo = htmlspecialchars($_GET["nuevoTitulo"]);
$boolEnUso = htmlspecialchars($_GET["enUso"]);



//endregion

$token = htmlspecialchars($_GET["token"]);

    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 

        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            // lo primero es comprobar que existe el elemento que se quiere modificar 
            if (!empty($idAmbiente) && !empty($nuevoTitulo) && $boolEnUso!=null) {
                // Tenemos todos los datos ok
                // Comprobamos que el id existe
                if ($cf->comprobarExisteAmbientePorId($idAmbiente)) {
            
                    $dao->actualizarAmbiente($nuevoTitulo, $boolEnUso, $idAmbiente);
                    echo $logger->updated_element();
                } else {
                    echo $logger->not_exists("ambiente");
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