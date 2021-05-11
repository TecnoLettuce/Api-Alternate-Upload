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
    include_once '../../objects/user.php';
    include_once '../../objects/session.php';
    include_once '../../util/commonFunctions.php';
    include_once '../../objects/DAO.php';
    include_once '../../util/logger.php';
    $logger = new Logger();
    $dao = new Dao();

    $database = new Database();
    $common = new CommonFunctions();
    // recogemos los datos pasados 
    $data = json_decode(file_get_contents("php://input"));

    // Aqui tengo que recibir usuario, contraseña, mail, el rol (opcional), y el token de quien crea el user
    // La logica es que si el token no coincide, no se puede crear un usuario

    $userNameRecibido = $data->username;
    $passwordRecibida = $data->password;
    $mailRecibido = $data->mail;
    $rolRecibido = $data->rol;
    $tokenRecibido = $data->token;
    

    // Comprobamos que todo esta en orden 
    // echo "Datos recibidos > ".$userNameRecibido." > ".$passwordRecibida." > ".$mailRecibido." > ".$rolRecibido." > ".$tokenRecibido;

    // Comprobamos que no faltan datos esenciales 
    if (!empty($userNameRecibido) && !empty($passwordRecibida) && !empty($mailRecibido) && !empty($tokenRecibido)) {

        // Comprobamos si el rol está asignado 
        $rolRecibido = comprobarRol($rolRecibido);
        // Comprobamos si el token es valido 
        $tokenValido = $common->comprobarTokenAdmin($tokenRecibido);
        //$tokenValido = comprobarToken($tokenRecibido);

        if ($tokenValido == -1) {
            http_response_code(403);
            echo $logger->invalid_token();
        } else if ($tokenValido == 0) {
            http_response_code(403);
            echo $logger->not_permission();
        } else {
            $passwordRecibida = sha1($passwordRecibida, $raw_output = false);
            $idUser = crearUsuario($userNameRecibido, $passwordRecibida, $mailRecibido, $rolRecibido);
            http_response_code(201);
            echo $logger->created_element(); 
        }
        
    } else {
        http_response_code(400);
        echo $logger->incomplete_data();
    }


    /**
     * Recibe todos los parametros para crear al usuario
     * @param username, password, mail, rol
     * @return Void
     */
    function crearUsuario ($username, $password, $mail, $rol) {
        $database = new Database();
        // Lanzamos la consulta para crear el user
        $query = "INSERT INTO user (idUser, userName, password, mail, idRol) VALUES (null, '".$username."', '".$password."', '".$mail."', ".$rol.");";
        //echo "\nConsulta para insertar > ".$query."\n";
        $stmt = $database->getConn()->prepare($query);
        // ejecutamos la inserción
        $stmt->execute();

    } 

    /**
     * Recibe el rol que se recoge por CGI
     * Comprueba si está asignado, si no lo está, le asigna un rol 
     * @param $rol
     * @return integer 
     */
    function comprobarRol ($rol) {
        // Si todo esto tiene valores, se comprueba que el rol está especificado
        if (empty($rol)) {
            // No hay rol asignado, por defecto se le da 1
            $rol = 1;
            return $rol;
        } else {
            // El rol ya está asignado
            return $rol; 
        }
    }

?>