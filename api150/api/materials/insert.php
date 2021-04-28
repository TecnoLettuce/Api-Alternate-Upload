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


    /**
     * Aquí va la lógica de subir un archivo por url 
     */

    $url = $data->url;
    $tipo = $data->id_Tipo;
    $token = $data->token;

    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 

        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            // comprobamos que no faltan datos vitales
            if (!empty($url) && !empty($tipo)) {
                // Tenemos todos los datos 
                // Comprobar si existe el medio 
                if ($cf->comprobarExisteMedioPorURL($url)) {
                    // Ya existe 
                    echo json_encode("status : 406, message : El elemento que intenta insertar ya existe");
                } else {
                    // No existe 
                    // lo insertamos 
                    // el programa no existe 
                    $query = "INSERT INTO medios (id_Medio, url, id_Tipo) VALUES (null,'".$url."',".$tipo.");";
                    // echo $query;
                    // echo "La consulta para insertar un programa es ".$query;
                    $stmt = $database->getConn()->prepare($query);
                    // echo "La consulta para insertar el programa es ".$query;
                    
                    $stmt->execute();
                    echo json_encode(array("status : 0, message : Elemento creado"));
                }
            } else {
                echo json_encode("status : 400, message : Faltan uno o más datos");
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