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
    include_once '../../util/historia.php';

    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();

    // No tiene que recibir parámetros es solo la consulta pelada
    $query = "SELECT * FROM historias;";
    $resultado = $database->getConn()->query($query);
    
    $arr = array();
    while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
        $historia = new Historia();
        $historia->idHistoria=$row["id_Historia"];
        $historia->titulo=$row["titulo"];
        $historia->subtitulo=$row["subtitulo"];
        $historia->descripcion=$row["descripcion"];
        $historia->enUso=$row["enUso"];
        array_push($arr, $historia);
    }
    echo json_encode($arr);
?>
