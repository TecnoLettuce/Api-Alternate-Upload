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

    $tituloHistoriaRecibido = $data->tituloHistoria;
    $subtituloHistoriaRecibido = $data->subtituloHistoria;
    $descripcionRecibida = $data->descripcion;
    $boolEnUso = $data->enUso;
    $token = $data->token;

    //endregion


    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 
        // comprobamos que no faltan datos vitales
        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            if (!empty($tituloHistoriaRecibido) && !empty($subtituloHistoriaRecibido) && !empty($descripcionRecibida) && $boolEnUso != null ) {
                // Tenemos todos los datos
                //Comprobamos que el registro no existe ya en la base de datos 
                if ($cf->comprobarExisteHistoriaPorTitulo($tituloHistoriaRecibido)) {
                    // la Historia ya existe
                    echo json_encode(array("status : 406, message : La historia ya existe" ));
                } else {
                    // la historia no existe 
                    $query = "INSERT INTO historias (id_Historia, titulo, subtitulo, descripcion, enUso) VALUES (null,'".$tituloHistoriaRecibido."','".$subtituloHistoriaRecibido."', '".$descripcionRecibida."', ".$boolEnUso.");";
                    // echo "La consulta para insertar una historia es ".$query;
                    $stmt = $database->getConn()->prepare($query);
                    // echo "La consulta para insertar la historia es ".$query;
                    
                    $stmt->execute();
                    echo json_encode(array("status : 200, message : Elemento creado"));
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