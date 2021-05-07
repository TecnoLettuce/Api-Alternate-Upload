<?php 
    //region imports
    header("Content-Type: application/json; charset=UTF-8");
    // Creo que estas 3 líneas resuelven el problema de las CORS
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Access-Control-Allow-Headers, Authorization, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    //endregion

    // Conexión con la base de datos 
    include_once '../../config/database.php';
    include_once '../../util/commonFunctions.php';
    include_once '../../util/uploadFilesByURL.php';
    include_once '../../util/logger.php';

    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();
    $ucf = new UploadCommonFunctions();
    $logger = new Logger();

    //region Definicion de los datos que llegan
    $data = json_decode(file_get_contents("php://input"));

    $tituloHistoriaRecibido = $data->tituloHistoria;
    $subtituloHistoriaRecibido = $data->subtituloHistoria;
    $descripcionRecibida = $data->descripcion;
    $boolEnUso = $data->enUso;
    $token = $data->token;
    // Datos de los medios

    $arrayMedios = array();
    $arrayMedios = $data->medios;

    $mediosAInsertar = array();
    $tiposAInsertar = array();



    for ($i=0; $i < count($arrayMedios, COUNT_NORMAL); $i++) { 
        array_push($mediosAInsertar, $arrayMedios[$i]->url);
        array_push($tiposAInsertar, $arrayMedios[$i]->tipo);
    }


    //endregion


    // Comprobamos que tiene permisos de administrador
    if ($cf->comprobarTokenAdmin($token) == 1) { 
        // comprobamos que no faltan datos vitales
        if ($cf->comprobarExpireDate($token)) {
            // La sesión es válida
            $cf->actualizarExpireDate($token);
            if (!empty($tituloHistoriaRecibido) && !empty($subtituloHistoriaRecibido) && !empty($descripcionRecibida) && $boolEnUso != null ) {
                // Tenemos todos los datos
                //Comprobamos que el registro no existe ya en la base de datos 
                if ($cf->comprobarExisteHistoriaPorTitulo($tituloHistoriaRecibido)) {
                    // la Historia ya existe
                    $logger->already_exists("historia");
                } else {
                    // la historia no existe 

                    // Hay medios para insertar? 
                    if (!empty($mediosAInsertar) && !empty($tiposAInsertar) && ( count($tiposAInsertar, COUNT_NORMAL) == count($mediosAInsertar, COUNT_NORMAL))) {
                        // Hay medios para insertar 
                        // Insertamos los medios
                        $resultadoMedios = $ucf->insertarMedios($mediosAInsertar, $tiposAInsertar, $token);
                        

                        // Comprobamos el resultado
                        if (is_array($resultadoMedios)) {
                            // Tenemos array de ids

                            // Insertamos la historia
                            $query = "INSERT INTO historias (id_Historia, titulo, subtitulo, descripcion, enUso) VALUES (null,'".$tituloHistoriaRecibido."','".$subtituloHistoriaRecibido."', '".$descripcionRecibida."', ".$boolEnUso.");";
                            // echo "La consulta para insertar una historia es ".$query;
                            $stmt = $database->getConn()->prepare($query);
                            // echo "La consulta para insertar la historia es ".$query;
                            $stmt->execute();
        
                            
                            // Consultar el elemento que se acaba de insertar
                            $query = "SELECT id_Historia FROM historias WHERE titulo LIKE '".$tituloHistoriaRecibido."';";
                            $resultado = $database->getConn()->query($query);
                            $idObtenida = -1;
                            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                                $idObtenida = $row["id_Historia"];
                            }

                            // Comprobamos que la id obtenida es válida
                            if ($idObtenida < 0) {
                                // No es valida
                                $logger->fatal_error("Algo ha ido mal extrayendo la id");
                            } else {
                                // Tenemos la id y los medios insertados

                                // Insertamos las relaciones
                                $relacionesInsertadas = array();
                                for ($i=0; $i < count($resultadoMedios, COUNT_NORMAL); $i++) { 
                                    $query = "INSERT INTO rel_historia( id_Medio, id_Historia) VALUES (".$resultadoMedios[$i].",".$idObtenida.");";
                                    // echo "La consulta para insertar las relaciones es es ".$query;
                                    $stmt = $database->getConn()->prepare($query);
                                    $stmt->execute();
                                    array_push($relacionesInsertadas, $logger->created_element());
                                } // Salida del for
                                echo json_encode($relacionesInsertadas);
                            }

                        } else {
                            // Ha dado fallo
                            echo $resultado;
                        }
                    } else {
                        // No hay medios para insertar
                        // Insertamos solo la historia
                        // Insertamos la historia
                        $query = "INSERT INTO historias (id_Historia, titulo, subtitulo, descripcion, enUso) VALUES (null,'".$tituloHistoriaRecibido."','".$subtituloHistoriaRecibido."', '".$descripcionRecibida."', ".$boolEnUso.");";
                        // echo "La consulta para insertar una historia es ".$query;
                        $stmt = $database->getConn()->prepare($query);
                        // echo "La consulta para insertar la historia es ".$query;
                        $stmt->execute();

                        $logger->created_element();
                    }                        
                }
            } else {
                $logger->incomplete_data();
            }
        } else {
            $logger->expired_session();
        }
    } elseif ($cf->comprobarTokenAdmin($token) == 0) {
        $logger->not_permission();
    } else {
        $logger->invalid_token();
    }
?>