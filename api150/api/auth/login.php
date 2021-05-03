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


    $database = new Database();

    /**
     * Comprobación de que la conexión es valida
     */

    // echo "\nEsto es lo que posee el objeto db > ".$database->getHost()."/".$database->getDbName()."/".$database->getUsername()."/".$database->getPassword();

    // recoger los datos que se pasan por post 
    $data = json_decode(file_get_contents("php://input"));

    $nombreRecibidoPorGet = $data->username;
    $passwordRecibidaPorGet = $data->password;

    // Mirar a ver si hay datos 
    if (!empty($nombreRecibidoPorGet) && !empty($passwordRecibidaPorGet)) {

        // Ciframos la contraseña para que se pueda comprarar
        $passwordRecibidaPorGet = sha1($passwordRecibidaPorGet, $raw_output = false);
        // echo "La contraseña cifrada es --> ".$passwordRecibidaPorGet;
        // Consultamos a ver si el usuario existe 
        $query = "SELECT idUser FROM user WHERE username LIKE '".$nombreRecibidoPorGet."' AND password LIKE '".$passwordRecibidaPorGet."';";
        //echo "\nLOG > Class LOGIN > esta es la consulta que estoy enviando al SQL para loguear --> ".$query;
        // declarar la query
        $resultado = $database->getConn()->query($query);
        //Esto debe devolver un ID de usuario, si es correcto, se crea la sesion 

        if ($resultado->rowCount() != 0 ) {
            $idObtenido;
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $idObtenido = $row["idUser"];
            }
            // Usuario existe
            // echo "\nLOG > Class LOGIN > Usuario Logado";
            // Aqui ahora se genera el token y se crea la sesion 
            $expireDateGenerated = generateExpireDate();
            $tokenGenerado = generateToken($nombreRecibidoPorGet, $expireDateGenerated);
            
            crearSesion($tokenGenerado, $idObtenido, $expireDateGenerated, $database);
            // echo "\nLOG > Class LOGIN > Si estas viendo esto, la session se creó correctamente";
            echo json_encode(array("token"=>$tokenGenerado)); 
            return true;
        } else {
            // Usuario no existe
            echo json_encode(array("token" => null));
            return false;
        }

        
    } else {
        // si faltan datos, se comunica 
        http_response_code(400);
        echo json_encode(array("token"=>null));
    }

    /**
     * Función que genera un token para la sesion
     * @renturn String
     */
    function generateToken($nombreRecibidoPorGet, $expireDateGenerated) {
        $cadena = $nombreRecibidoPorGet . $expireDateGenerated . random_int(0,1000);
        $token = sha1($cadena, $raw_output = false);
        return $token;
    }

    /**
     * Función que genera una sesion con los datos disponibles  NO FUNCIONA 
     */
    function crearSesion($tokenGenerado, $idObtenido, $expireDate, $database) {
        
        // Lanzamos la consulta para crear la sesion
        $query = "INSERT INTO session (idSession, idUser, token, expireDate) VALUES (null,".$idObtenido.",'".$tokenGenerado."',".$expireDate.");";
        //echo "LOG > Class LOGIN > esta es la consulta que estoy enviando al SQL para loguear --> ".$query;
        // declarar la query
        $stmt = $database->getConn()->prepare($query);
        // ejecutamos la inserción
        $stmt->execute();

    }

    function generateExpireDate() {

        $tiempoUnix = time();
        $expireDate = $tiempoUnix + 240;

        // echo "El expireDate Generado es ".$expireDate;
        return $expireDate; 
    }
?>
