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
    include_once '../../util/act.php';
    include_once '../../objects/DAO.php';

    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();
    $dao = new Dao();

    if (isset($_GET["idPrograma"]) && isset($_GET["titulo"]) ) {

        // Recibe el titulo o el ID de un historia y lo busca en la base de datos 
        $id = htmlspecialchars($_GET["idPrograma"]);
        $titulo = htmlspecialchars($_GET["titulo"]);
        echo $dao->listarUnActoPorIdyTitulo($id, $titulo);

    } else if (isset($_GET["idPrograma"])) {

        $id = htmlspecialchars($_GET["idPrograma"]);
        echo $dao->listarUnActoPorId($id);
        
    } else if (isset($_GET["titulo"])) {

        $titulo = htmlspecialchars($_GET["titulo"]);
        echo $dao->listarUnActoPorTitulo($titulo);
        
    } else {

        $log = new Logger();
        $log->incomplete_data();
        
    }

?>