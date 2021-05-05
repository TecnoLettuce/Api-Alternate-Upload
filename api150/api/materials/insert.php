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
    $url = array(); // Declaramos el array
    $url = $data->url; // Esto puede ser un array
    $tipo = array(); // Declaramos el array
    $tipo = $data->tipo; 
    $token = $data->token;

    /*
    Los datos se reciben correctamente
    echo "Contenido de la variable URL > ".$url[0];
    echo "Contenido de la variable URL > ".$url[1];
    */
    $idsParaDevolver = array();

    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 

        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            $cf->actualizarExpireDate($token);
            // comprobamos que no faltan datos vitales
            if ( (count($url, COUNT_NORMAL) > 0) && !empty($tipo)) {
                // Tenemos todos los datos 

                // Recorremos el array para hacer la operación de buscar 
                // Por cada elemento de su contenido 
                for ($i=0; $i < count($url, COUNT_NORMAL); $i++) { 
                    // Comprobar si existe el medio 
                    if ($cf->comprobarExisteMedioPorURL($url[$i])) {
                        // Ya existe 
                        echo json_encode("status : 406, message : El elemento que intenta insertar ya existe");
                    } else {
                        // No existe 
                        $urlParaInsertar = $url[$i];
                        $tipoParaInsertar = $tipo[$i];
                        $query = "INSERT INTO medios( url, id_Tipo) VALUES ('".$urlParaInsertar."' , (SELECT tipos.id_Tipo FROM tipos WHERE tipos.descripcion LIKE '".$tipoParaInsertar."'));";
                        // echo "DEBUG > Consulta que se manda a la inserción de medios > ".$query;
                        $stmt = $database->getConn()->prepare($query);
                        $stmt->execute();
                        // Ahora se hace la comprobación de que se ha insertado bien 
                        $query = "SELECT id_medio FROM medios WHERE url LIKE '".$urlParaInsertar."';";
                        $resultado = $database->getConn()->query($query);
                        $idObtenida = -1;
                        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                            $idObtenida = $row["id_medio"];
                        }

                        if ($idObtenida < 0) {
                            // Algo ha ido mal
                            echo json_encode("Fatal error : Algo ha ido mal en la consulta de inserción");
                        } else {
                            // echo json_encode(array("status : 0, message : Elemento creado"));
                            array_push($idsParaDevolver, $idObtenida);
                        }

                        
                    } // Salida del else de comprobación de existencia

                } // Salida del for

                echo json_encode($idsParaDevolver); // devolvemos las id

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