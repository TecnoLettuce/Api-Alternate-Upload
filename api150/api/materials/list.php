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

    // No tiene que recibir parámetros es solo la consulta pelada
    $query = "SELECT * FROM medios;";
    $resultado = $database->getConn()->query($query);
    
    $arr = array();
    
    while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
        $medio = new Medio();
        $medio->id=$row["id_Medio"];
        $medio->url=$row["url"];
        $medio->tipo=$row["id_Tipo"];
        array_push($arr, $medio);
    }
    echo json_encode($arr);
?>
