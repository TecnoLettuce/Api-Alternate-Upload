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

    // Recibe el fecha o el ID de una frase y lo busca en la base de datos 
    $id = htmlspecialchars($_GET["idMedio"]);
    $url = htmlspecialchars($_GET["URL"]);
    

    // echo "Valores recogidos > id -> ".$id." | fecha -> ".$fecha; 

    if (!empty($id) && !empty($url)) {
        // echo "Estoy en la rama de las 2 recogidas";
        // Están ambos valores
        echo buscarPorAmbos($id, $url);
    } elseif (!empty($id) && empty($url)) {
        // echo "Estoy en la rama de solo la id";

        // Está solo el id
        echo buscarPorId($id);
    } elseif (empty($id) && !empty($url)) {
        // echo "Estoy en la rama de solo fecha";

        // Está solo el fecha
        echo buscarPorFecha($url);
    } else {
        // No hay ninguno
        echo json_encode(" error : 400, message : Faltan uno o más datos");
    }

   
    /**
     * Recibe la id de una frase y busca por ella en la base de datos 
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
     * Recibe el fecha de una frase y busca por el en la base de datos 
     * @param string $fecha
     * @return Result Object
     */
    function buscarPorFecha($url) {
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
     * Recibe la id y el título de una frase y busca por ellos en la base de datos 
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