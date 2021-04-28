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
    include_once '../../objects/user.php';
    include_once '../../objects/session.php';
    include_once '../../util/commonFunctions.php';

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
            echo json_encode("El token de usuario que está utilizando no es válido");
        } else if ($tokenValido == 0) {
            echo json_encode("Está intentando crear un usuario sin permisos de administrador");
        } else {
            $passwordRecibida = sha1($passwordRecibida, $raw_output = false);
            $idUser = crearUsuario($userNameRecibido, $passwordRecibida, $mailRecibido, $rolRecibido);
            echo json_encode(array("error : 0")); // todo bien 
        }
        
    } else {
        echo json_encode(array("error : 1")); // Faltan datos
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