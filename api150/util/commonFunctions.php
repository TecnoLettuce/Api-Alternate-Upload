<?php
    include_once '../../config/database.php';
    include_once '../../objects/user.php';
    include_once '../../objects/session.php';
    include_once 'uploadFilesByURL.php';
class CommonFunctions {

    // Atributos
    // Constructor 
    public function __construct() {
    } 
    // Getters && Setters 
    // Metodos 

    /**
     * Recibe un token y comprueba si existe.
     * Tras esto, recoge el rol del usuario asociado a ese token
     * Si existe devolverá true y dejará realizar operaciones 
     * Si no existe devolverá false
     * @param $token
     * @return integer -1 si el token no es valido, 
     *                  0 si el token es valido pero el usuario no es admin, 
     *                  1 si el token es valido y usuario es admin 
     */
    function comprobarTokenAdmin ($token) {
        $database = new Database();
        $idObtenido = -1;
        $consulta = "SELECT idUser FROM session WHERE token LIKE '".$token."';";
        // echo "\n\nConsulta del token > ".$consulta."\n\n";
        $resultado = $database->getConn()->query($consulta);
        //Esto debe devolver un ID de usuario, si es correcto, se crea la sesion 
        if ($resultado->rowCount() == 0) {
            // El token del usuario que esta creando el nuevo usuario no existe 
            return -1;
        } else {
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $idObtenido = $row["idUser"];
            }
            // Se comprueba el rol del user
            $query = "SELECT idRol FROM user WHERE idUser LIKE '".$idObtenido."';";
            $resultado = $database->getConn()->query($query);
            $rolObtenido = -1;
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $rolObtenido = $row["idRol"];
            }
            if ($this->comprobarRolAdmin($rolObtenido)) {
                return 1;
            } else {
                return 0;
            }

        }  
    }

    /**
     * Recibe el numero de rol de un usuario y devuelve true en caso de que sea un admin.
     * en caso contrario, devuelve false
     * @param Integer 
     * @return Boolean
     */
    function comprobarRolAdmin($rol) {
        // El rol del administrador va a ser el 1
        if ($rol == 1) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * TODO
     * Recibe el token del usuario y comprueba si la sesión está expirada 
     * @param String token
     * @return boolean
     */
    public function comprobarExpireDate ($token) {
        $tiempoActual = time();
        // Recogemos el expireDate asociado al token que recibimos 
        $query = "SELECT expireDate FROM session WHERE token LIKE '".$token."';";
        $database = new Database();
        $resultado = $database->getConn()->query($query);
        $tiempoSession = "";
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $tiempoSession = $row["expireDate"];
        }

        // Convertimos el date a timestamp
        // $tiempoSessionTimestamp = strtotime($tiempoSession);


        if ($tiempoSession > $tiempoActual) {
            // Aqui el tiempo del expire es un tiempo más grande que el tiempo actual,
            // por lo tanto token valido
            return true;
        } else {
            return false;
        }


    }

    /** 
     * Funcion que se llama cada vez que el usuario realiza una acción
     * Otorga 2 minutos más de login desde el momento en el que se realiza la acción
     * @param String Token
     * @return Void
     */
    public function actualizarExpireDate($tokenUsuario) {
        $tiempoActual = time();
        $tiempoActual = $tiempoActual +240;  
        // Recogemos el expireDate asociado al token que recibimos 
        $query = "UPDATE session SET expireDate = ".$tiempoActual." WHERE token LIKE '".$tokenUsuario."';";
        // echo "CF -> ".$query;
        $database = new Database();
        $database->getConn()->query($query);
    }


    /**
     * Recibe el titulo de un ambiente y devuelve si existe o no
     * @param integer idAmbiente
     * @return boolean
     */
    public function comprobarExisteAmbientePorTitulo($titulo) {
        $database = new Database();
        $query = "SELECT id_Ambiente FROM ambiente WHERE titulo LIKE '".$titulo."';";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }

    }

    /**
     * Recibe el id de un ambiente y devuelve si existe o no
     * @param integer idAmbiente
     * @return boolean
     */
    public function comprobarExisteAmbientePorId($id) {
        $database = new Database();
        $query = "SELECT titulo FROM ambiente WHERE id_Ambiente LIKE ".$id.";";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }

    }



    /**
     * Recibe el titulo de una historia y devuelve si existe o no
     * @param integer tituloHistoria
     * @return boolean
     */
    public function comprobarExisteHistoriaPorTitulo($titulo) {
        $database = new Database();
        $query = "SELECT id_Historia FROM historias WHERE titulo LIKE '".$titulo."';";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }

    }

     /**
     * Recibe el id de una historia y devuelve si existe o no
     * @param integer idHistoria
     * @return boolean
     */
    public function comprobarExisteHistoriaPorId($id) {
        $database = new Database();
        $query = "SELECT titulo FROM historias WHERE id_Historia LIKE ".$id.";";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }

    }
 

    /**
     * Recibe el titulo de una historia y devuelve si existe o no
     * @param string titulo
     * @return boolean
     */
    public function comprobarExisteSaludoPorTitulo($titulo) {
        $database = new Database();
        $query = "SELECT id_Saludo FROM saludos WHERE titulo LIKE '".$titulo."';";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }

    }

     /**
     * Recibe el id de un saludo y devuelve si existe o no
     * @param integer id
     * @return boolean
     */
    public function comprobarExisteSaludoPorId($id) {
        $database = new Database();
        $query = "SELECT titulo FROM saludos WHERE id_Saludo LIKE ".$id.";";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }

    }



    /**
     * Recibe el titulo de una oración y devuelve si existe o no
     * @param string titulo
     * @return boolean
     */
    public function comprobarExisteOracionPorTitulo($titulo) {
        $database = new Database();
        $query = "SELECT id_Oracion FROM oraciones WHERE titulo LIKE '".$titulo."';";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }

    }

     /**
     * Recibe el id de una oración y devuelve si existe o no
     * @param integer id
     * @return boolean
     */
    public function comprobarExisteOracionPorId($id) {
        $database = new Database();
        $query = "SELECT titulo FROM oraciones WHERE id_Oracion LIKE ".$id.";";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }

    }

   


    /**
     * Recibe el titulo de una visita y devuelve si existe o no
     * @param string titulo
     * @return boolean
     */
    public function comprobarExisteVisitaPorTitulo($titulo) {
        $database = new Database();
        $query = "SELECT id_Visita FROM visitas WHERE titulo LIKE '".$titulo."';";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }

    }

     /**
     * Recibe el id de una visita y devuelve si existe o no
     * @param integer id
     * @return boolean
     */
    public function comprobarExisteVisitaPorId($id) {
        $database = new Database();
        $query = "SELECT titulo FROM visitas WHERE id_Visita  LIKE ".$id.";";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }

    }

    /**
     * Recibe el titulo de un acto y devuelve si existe o no
     * @param string titulo
     * @return boolean
     */
    public function comprobarExisteActoPorTitulo($titulo) {
        $database = new Database();
        $query = "SELECT id_Programa FROM programas WHERE titulo LIKE '".$titulo."';";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }

    }

     /**
     * Recibe el id de una Acto y devuelve si existe o no
     * @param integer id
     * @return boolean
     */
    public function comprobarExisteActoPorId($id) {
        $database = new Database();
        $query = "SELECT titulo FROM programas WHERE id_Programa  LIKE ".$id.";";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }

    }


    /**
     * Recibe la url de un medio y devuelve si existe o no
     * @param string url
     * @return boolean
     */
    public function comprobarExisteMedioPorURL($url) {
        $database = new Database();
        $query = "SELECT id_Medio FROM medios WHERE url LIKE '".$url."';";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }

    }

     /**
     * Recibe el id de un medio y devuelve si existe o no
     * @param integer id
     * @return boolean
     */
    public function comprobarExisteMedioPorId($id) {
        $database = new Database();
        $query = "SELECT url FROM medios WHERE id_Medio  LIKE ".$id.";";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }
    }


    /**
     * Recibe el titulo de una frase y devuelve si existe o no
     * @param string titulo
     * @return boolean
     */
    public function comprobarExisteFrasePorFecha($fecha) {
        $database = new Database();
        $query = "SELECT id_Frase FROM frase_inicio WHERE fecha LIKE '".$fecha."';";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }

    }

     /**
     * Recibe el id de una frase y devuelve si existe o no
     * @param integer id
     * @return boolean
     */
    public function comprobarExisteFrasePorId($id) {
        $database = new Database();
        $query = "SELECT id_Frase FROM frase_inicio WHERE id_Frase  LIKE ".$id.";";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }
    }

    

    /**
     * Recibe el titulo de un himno y devuelve si existe o no
     * @param string titulo
     * @return boolean
     */
    public function comprobarExisteHimnoPortitulo($titulo) {
        $database = new Database();
        $query = "SELECT id_Himno FROM himnos WHERE titulo LIKE '".$titulo."';";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }

    }

     /**
     * Recibe el id de un himno y devuelve si existe o no
     * @param integer id
     * @return boolean
     */
    public function comprobarExisteHimnoPorId($id) {
        $database = new Database();
        $query = "SELECT id_Himno FROM himnos WHERE id_Himno  LIKE ".$id.";";
        $resultado = $database->getConn()->query($query);

        if ($resultado->rowCount() != 0) {
            return true;    
        } else {
            return false;
        }
    }

    

}

?>