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
    include_once '../../objects/DAO.php';
    include_once '../../util/logger.php';

    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();
    $logger = new Logger();
    $dao = new Dao();

    if (isset($_GET["idFrase"]) && isset($_GET["fecha"]) ) {

        // Recibe el titulo o el ID de una frase y lo busca en la base de datos 
        $id = htmlspecialchars($_GET["idFrase"]);
        $fecha = htmlspecialchars($_GET["fecha"]);
        echo $dao->listarUnaFrasePorIdyFecha($id,$fecha);

    } else if (isset($_GET["idFrase"])) {

        $id = htmlspecialchars($_GET["idFrase"]);
        echo $dao->listarUnaFrasePorId($id);
        
    } else if (isset($_GET["fecha"])) {

        $fecha = htmlspecialchars($_GET["fecha"]);
        echo $dao->listarUnaFrasePorFecha($fecha);
        
    } else {

        $logger->incomplete_data();
        
    }

?>