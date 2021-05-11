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
    include_once '../../util/material.php';

    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();

    if (isset($_GET["idMedio"]) && isset($_GET["URL"])) {
        // Recibe el fecha o el ID de una frase y lo busca en la base de datos 
        $id = htmlspecialchars($_GET["idMedio"]);
        $url = htmlspecialchars($_GET["URL"]);
        echo BuscarPorAmbos($id, $url);

    } else if (isset($_GET["idMedio"])) {
        $id = htmlspecialchars($_GET["idMedio"]);
        echo BuscarPorId($id);
    } else if (isset($_GET["URL"])) {
        $url = htmlspecialchars($_GET["URL"]);
        echo buscarPorURL($url);
    } else {
        $logger = new Logger();
        $logger->incomplete_data();
    }

    
   
    /**
     * Recibe la id de un medio y busca por ella en la base de datos 
     * @param integer $id
     * @return Result Object
     */
    function buscarPorId($id) {
        $query = "SELECT * FROM medios WHERE id_Medio LIKE ".$id.";";
        $database = new Database();
        $resultado = $database->getConn()->query($query);
        
        $arr = array();
        
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $medio = new Medio();
            $medio->id=$row["id_Medio"];
            $medio->url=$row["url"];
            $medio->tipo=$row["id_Tipo"];
            array_push($arr, $medio);
        }
        $paraDevolver = json_encode($arr);
        return $paraDevolver;
    }
    /**
     * Recibela url de un material y busca por ella en la base de datos 
     * @param string $fecha
     * @return Result Object
     */
    function buscarPorURL($url) {
        $query = "SELECT * FROM medios WHERE url LIKE '".$url."';";
        $database = new Database();
        $resultado = $database->getConn()->query($query);
        
        $arr = array();
        
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $medio = new Medio();
            $medio->id=$row["id_Medio"];
            $medio->url=$row["url"];
            $medio->tipo=$row["id_Tipo"];
            array_push($arr, $medio);
        }
        $paraDevolver = json_encode($arr);
        return $paraDevolver;

    }

    /**
     * Recibe la id y la URL de un material y busca por ellos en la base de datos 
     * @param integer $id string $fecha
     * @return Result Object
     */
    function buscarPorAmbos($id, $url) {
        $query = "SELECT * FROM medios WHERE id_Medio LIKE ".$id." AND url LIKE '".$url."';";
        $database = new Database();
        $resultado = $database->getConn()->query($query);
        
        $arr = array();
        
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $medio = new Medio();
            $medio->id=$row["id_Medio"];
            $medio->url=$row["url"];
            $medio->tipo=$row["id_Tipo"];
            array_push($arr, $medio);
        }
        $paraDevolver = json_encode($arr);
        return $paraDevolver;

    }

?>