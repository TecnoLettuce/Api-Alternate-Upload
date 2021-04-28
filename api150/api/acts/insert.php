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
    
    $titulo = $data->titulo;
    $fecha = $data->fecha;
    $boolEnUso = $data->enUso;
    $categoria = $data->categoria;
    $token = $data->token;


    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 
        // Comprobamos que su sesión no ha caducado
        if ($cf->comprobarExpireDate($token)) {
            // La sesión no ha caducado, por lo que seguimos adelante y le otorgamos 2 minutos más
            

            $cf->actualizarExpireDate($token); // NO FUNCIONA


            // comprobamos que no faltan datos vitales
            if (!empty($titulo) && !empty($categoria) && !empty($fecha) && $boolEnUso!=null) {
                // Tenemos todos los datos
                //Comprobamos que el registro no existe ya en la base de datos 
                if ($cf->comprobarExisteActoPorTitulo($titulo)) {
                    // el programa ya existe
                    echo json_encode(array("status : 406, message : El acto ya existe" ));
                } else {
                    // el programa no existe 
                    $query = "INSERT INTO programas (id_Programa, titulo, fecha, enUso, id_Categoria) VALUES (null,'".$titulo."','".$fecha."',".$boolEnUso.", '".$categoria."');";
                    // echo "La consulta para insertar un programa es ".$query;
                    $stmt = $database->getConn()->prepare($query);
                    // echo "La consulta para insertar el programa es ".$query;
                    
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