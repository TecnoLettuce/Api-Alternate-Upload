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
    include_once '../../util/phrase.php';

    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();

    // No tiene que recibir parámetros es solo la consulta pelada
    $query = "SELECT * FROM frase_inicio;";
    $resultado = $database->getConn()->query($query);
    
    $arr = array();
    
    while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
        $frase = new Frase();
        $frase->id=$row["id_Frase"];
        $frase->texto=$row["texto"];
        $frase->fecha=$row["fecha"];
        $frase->enUso=$row["enUso"];
        array_push($arr, $frase);
    }
    echo json_encode($arr);
?>
