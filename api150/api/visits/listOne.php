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
    include_once '../../util/visit.php';
    include_once '../../objects/DAO.php';
    include_once '../../util/logger.php';

    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();
    $logger = new Logger();
    $dao = new Dao();


    if (isset($_GET["idVisita"]) && isset($_GET["titulo"])) {
        // Recibe el titulo o el ID de un visita y lo busca en la base de datos 
        $id = htmlspecialchars($_GET["idVisita"]);
        $titulo = htmlspecialchars($_GET["titulo"]);

        echo $dao->listarVisitaPorIdyTitulo($id,$titulo);

    } else if (isset($_GET["idVisita"])) {
        $id = htmlspecialchars($_GET["idVisita"]);
        echo $dao->listarVisitaPorId($id);
    } else if (isset($_GET["titulo"])) {
        $titulo = htmlspecialchars($_GET["titulo"]);
        echo $dao->listarVisitaPorTitulo($titulo);
    } else {
        http_response_code(400);
        $logger->incomplete_data();
    }
   
?>