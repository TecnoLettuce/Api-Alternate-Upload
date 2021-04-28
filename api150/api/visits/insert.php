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

    $tituloVisita = $data->tituloVisita;
    $token = $data->token;
    //endregion


    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 

        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            // comprobación de que los datos se reciben correctamente
            if (!empty($tituloVisita)) {
                // tengo todos los datos que necesito
                //Comprobamos que el registro no existe ya en la base de datos 
                if ($cf->comprobarExisteVisitaPorTitulo($tituloVisita)) {
                    // El visita ya existe
                    echo json_encode(array("status : 406, message : La visita ya existe" ));
                } else {
                    // el visita no existe 
                    $query = "INSERT INTO visitas (id_Visita, titulo) VALUES (null,'".$tituloVisita."');";
                    // echo "La consulta para insertar un visita es ".$query;
                    $stmt = $database->getConn()->prepare($query);
                        
                    $stmt->execute();

                    echo json_encode(array("status : 200, message : Elemento creado"));
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