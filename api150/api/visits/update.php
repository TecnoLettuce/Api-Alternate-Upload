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

$idVisita = htmlspecialchars($_GET["idVisita"]);
$nuevoTitulo = htmlspecialchars($_GET["nuevoTitulo"]);

//endregion

$token = htmlspecialchars($_GET["token"]);

    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 

        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            // lo primero es comprobar que existe el elemento que se quiere modificar 
            if (!empty($idVisita) && !empty($nuevoTitulo) ) {
                // Tenemos todos los datos ok
                // Comprobamos que el id existe
                if ($cf->comprobarExisteVisitaPorId($idVisita)) {
            
                    $database = new Database();
                    $query = "UPDATE visitas SET titulo = '".$nuevoTitulo."' WHERE id_Visita LIKE ".$idVisita.";";
                    $stmt = $database->getConn()->prepare($query);
                    $stmt->execute();
                    echo json_encode(" status : 200, message : Elemento actualizado");
                } else {
                    echo json_encode(" status : 406, message : El registro no existe");
                }
            } else {
                echo json_encode(" status : 400, message : Faltan uno o más datos");
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