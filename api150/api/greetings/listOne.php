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
    include_once '../../util/greetings.php';
    include_once '../../objects/DAO.php';
    include_once '../../util/logger.php';

    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();
    $dao = new Dao();
    $logger = new Logger();


    // Recibe el titulo o el ID de un historia y lo busca en la base de datos 
    if (isset($_GET["idSaludo"]) && isset($_GET["titulo"]) ) {

        
        $id = htmlspecialchars($_GET["idSaludo"]);
        $titulo = htmlspecialchars($_GET["titulo"]);
        echo $dao->listarUnSaludoPorIdyTitulo($id,$titulo);

    } else if (isset($_GET["idSaludo"])) {

        $id = htmlspecialchars($_GET["idSaludo"]);
        echo $dao->listarUnSaludoPorId($id);
        
    } else if (isset($_GET["titulo"])) {

        $titulo = htmlspecialchars($_GET["titulo"]);
        echo $dao->listarUnSaludoPorTitulo($titulo);
        
    } else {

        $logger->incomplete_data();
        
    }

   

?>