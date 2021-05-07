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
 
    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();
 
    //region Definicion de los datos que llegan
    $data = json_decode(file_get_contents("php://input"));


    /**
     * Aquí va la lógica de subir un archivo por url 
     */
    $url = array(); // Declaramos el array
    $url = $data->url; // Esto puede ser un array
    $tipo = array(); // Declaramos el array
    $tipo = $data->tipo; 
    $token = $data->token;
    $ucf = new UploadCommonFunctions();

    $result = array();
    $result = $ucf->insertarMedios($url, $tipo, $token);

    // Comprobar si ha devuelto fallo o acierto 
    if (count($result, COUNT_NORMAL) > 0) {
        // Es un array y tenemos resultados 
        echo json_encode($result);
    } else {
        // Es un código de error
        echo $result;
    }