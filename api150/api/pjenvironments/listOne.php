<?php 

    //region imports
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Access-Control-Allow-Headers, Authorization, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD');
    header("Access-Control-Max-Age: 3600");
    //endregion

    // Conexión con la base de datos 
    include_once '../../config/database.php';
    include_once '../../util/commonFunctions.php';
    include_once '../../util/ambiente.php';
    include_once '../../objects/DAO.php';
    include_once '../../util/logger.php';
    $logger = new Logger();
    $dao = new Dao();

    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();
    $logger = new Logger();
    $dao = new Dao();

    if (isset($_GET["idAmbiente"]) && isset($_GET["titulo"]) ) {

        // Recibe el titulo o el ID de un historia y lo busca en la base de datos 
        $id = htmlspecialchars($_GET["idAmbiente"]);
        $titulo = htmlspecialchars($_GET["titulo"]);
        echo $dao->ListarUnAmbientePorIdyTitulo($id,$titulo);

    } else if (isset($_GET["idAmbiente"])) {

        $id = htmlspecialchars($_GET["idAmbiente"]);
        echo $dao->ListarUnAmbientePorId($id);
        
    } else if (isset($_GET["titulo"])) {

        $titulo = htmlspecialchars($_GET["titulo"]);
        echo $dao->ListarUnAmbientePorTitulo($titulo);
        
    } else {

        $log = new Logger();
        $log->incomplete_data();
        
    }

   
    /**
     * Recibe la id de un ambiente y busca por ella en la base de datos 
     * @param integer $id
     * @return Result Object
     */
    function buscarPorId($id) {
        
    }
    /**
     * Recibe el titulo de un ambiente y busca por el en la base de datos 
     * @param string $titulo
     * @return Result Object
     */
    function buscarPorTitulo($titulo) {
        $query = "SELECT * FROM ambiente WHERE titulo LIKE '".$titulo."';";
        $database = new Database();
        $resultado = $database->getConn()->query($query);
        
        $arr = array();
        
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $ambiente = new ambiente();
            $ambiente->id=$row["id_Ambiente"];
            $ambiente->titulo=$row["titulo"];
            $ambiente->descripcion=$row["descripcion"];
            $ambiente->enUso=$row["enUso"];

            array_push($arr, $ambiente);
        }
        $paraDevolver = json_encode($arr);
        return $paraDevolver;

    }

    /**
     * Recibe la id y el título de un ambiente y busca por ellos en la base de datos 
     * @param integer $id string $titulo
     * @return Result Object
     */
    function buscarPorAmbos($id, $titulo) {
        $query = "SELECT * FROM ambiente WHERE id_Ambiente LIKE ".$id." AND titulo LIKE '".$titulo."';";
        $database = new Database();
        $resultado = $database->getConn()->query($query);
        
        $arr = array();
        
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $ambiente = new ambiente();
            $ambiente->id=$row["id_Ambiente"];
            $ambiente->titulo=$row["titulo"];
            $ambiente->descripcion=$row["descripcion"];
            $ambiente->enUso=$row["enUso"];

            array_push($arr, $ambiente);
        }
        $paraDevolver = json_encode($arr);
        return $paraDevolver;

    }

?>