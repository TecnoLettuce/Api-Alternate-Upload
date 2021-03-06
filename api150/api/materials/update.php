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

$idMedio = htmlspecialchars($_GET["idMedio"]);
$nuevaURL = htmlspecialchars($_GET["nuevaURL"]);
//endregion

$token = htmlspecialchars($_GET["token"]);

    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 

        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            // lo primero es comprobar que existe el elemento que se quiere modificar 
            if (!empty($idMedio) && !empty($nuevaURL)) {
                // Tenemos todos los datos ok
                // Comprobamos que el id existe
                if ($cf->comprobarExisteMedioPorId($idMedio)) {
            
                    $database = new Database();
                    $query = "UPDATE medios SET url = '".$nuevaURL."' WHERE id_Medio LIKE ".$idMedio.";";
                    $stmt = $database->getConn()->prepare($query);
                    $stmt->execute();
                    http_response_code(200);
                    echo json_encode(" status : 200, message : Elemento actualizado");
                } else {
                    http_response_code(406);
                    echo json_encode(" status : 406, message : El registro no existe");
                }
            } else {
                http_response_code(400);
                echo json_encode(" status : 400, message : Faltan uno o más datos");
            }
        } else {
            http_response_code(401);
            echo json_encode("status : 401, message : Tiempo de sesión excedido");
        }

    } elseif ($cf->comprobarTokenAdmin($token) == 0) {
        http_response_code(403);
        echo json_encode("status : 401, message : no tiene permisos para realizar esta operación");
    } else {
        http_response_code(403);
        echo json_encode("status : 403, message : token no valido");
    }



?>