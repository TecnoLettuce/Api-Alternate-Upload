<?php 

//region imports
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Access-Control-Allow-Headers, Authorization, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD');
header("Access-Control-Max-Age: 3600");
//endregion

/* cosa de la que no me fio */
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Indica los métodos permitidos.
    header('Access-Control-Allow-Methods: GET, POST, DELETE');
    // Indica los encabezados permitidos.
    header('Access-Control-Allow-Headers: Authorization');
    http_response_code(204);
}

// Conexión con la base de datos 
include_once '../../config/database.php';
include_once '../../util/commonFunctions.php';
//Creación de la base de datos 
$database = new Database();
// Declaración de commonFunctions
$cf = new CommonFunctions();
//region Definicion de los datos que llegan
$data = json_decode(file_get_contents("php://input"));

$idHistoria = htmlspecialchars($_GET["idHistoria"]);
$nuevoTitulo = htmlspecialchars($_GET["nuevoTitulo"]);
$nuevoSubtitulo = htmlspecialchars($_GET["nuevoSubtitulo"]);
$nuevaDescripcion = htmlspecialchars($_GET["nuevaDescripcion"]);
$boolEnUso = htmlspecialchars($_GET["enUso"]);



//endregion

$token = htmlspecialchars($_GET["token"]);

    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 
        // lo primero es comprobar que existe el elemento que se quiere modificar 
        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            if (!empty($idHistoria) && !empty($nuevoTitulo) && !empty($nuevoSubtitulo) && !empty($nuevaDescripcion) && $boolEnUso != null) {
                // Tenemos todos los datos ok
                // Comprobamos que el id existe
                if ($cf->comprobarExisteHistoriaPorId($idHistoria)) {
            
                    $database = new Database();
                    $query = "UPDATE historias SET titulo = '".$nuevoTitulo."',subtitulo = '".$nuevoSubtitulo."',descripcion = '".$nuevaDescripcion."', enUso = ".$boolEnUso." WHERE id_Historia LIKE ".$idHistoria.";";
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
        http_response_code(401);
        echo json_encode("status : 401, message : no tiene permisos para realizar esta operación");
    } else {
        http_response_code(403);
        echo json_encode("status : 403, message : token no valido");
    }



?>