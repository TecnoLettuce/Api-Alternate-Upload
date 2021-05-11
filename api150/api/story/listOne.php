<?php 

    //region imports
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Access-Control-Allow-Headers, Authorization, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD');
    header("Access-Control-Max-Age: 3600");
    //endregion

    /* cosa de la que no me fio */
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        // Indica los métodos permitidos.
        header('Access-Control-Allow-Methods: GET, POST, DELETE');
        // Indica los encabezados permitidos.
        header('Access-Control-Allow-Headers: Authorization');
        http_response_code(204);
    }

    // Conexión con la base de datos 
    include_once '../../config/database.php';
    include_once '../../util/commonFunctions.php';
    include_once '../../util/historia.php';
    include_once '../../util/logger.php';

    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();

    if (isset($_GET["idHistoria"]) && isset($_GET["titulo"]) ) {

        // Recibe el titulo o el ID de un historia y lo busca en la base de datos 
        $id = htmlspecialchars($_GET["idHistoria"]);
        $titulo = htmlspecialchars($_GET["titulo"]);
        echo buscarPorAmbos($id, $titulo);

    } else if (isset($_GET["idHistoria"])) {

        $id = htmlspecialchars($_GET["idHistoria"]);
        echo buscarPorId($id);
        
    } else if (isset($_GET["titulo"])) {

        $titulo = htmlspecialchars($_GET["titulo"]);
        echo buscarPorTitulo($titulo);
        
    } else {

        $log = new Logger();
        http_response_code(406);
        $log->incomplete_data();
        
    }
 
   
    /**
     * Recibe la id de un acto y busca por ella en la base de datos 
     * @param integer $id
     * @return Result Object
     */
    function buscarPorId($id) {
        $query = "SELECT historias.id_Historia, historias.titulo, historias.subtitulo, historias.descripcion AS 'Desc_Historia', historias.enUso, medios.url, tipos.descripcion FROM historias INNER JOIN rel_historia ON historias.id_Historia=rel_historia.id_Historia INNER JOIN medios ON rel_historia.id_Medio=medios.id_Medio INNER JOIN tipos ON medios.id_Tipo = tipos.id_Tipo WHERE historias.id_Historia LIKE ".$id.";";

        $database = new Database();
        $resultado = $database->getConn()->query($query);
        
        $arrayMedios = array();
        
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $historia = new Historia();
            $historia->idHistoria=$row["id_Historia"];
            $historia->titulo=$row["titulo"];
            $historia->subtitulo=$row["subtitulo"];
            $historia->descripcion=$row["Desc_Historia"];
            $historia->enUso=$row["enUso"];
            array_push($arrayMedios, array("url"=> $row["url"], "tipo"=> $row["descripcion"]) );
        }
        $historia->medios = $arrayMedios;
        $paraDevolver = json_encode($historia);
        return $paraDevolver;
    }
    /**
     * Recibe el titulo de un acto y busca por el en la base de datos 
     * @param string $titulo
     * @return Result Object
     */
    function buscarPorTitulo($titulo) {
        $query = "SELECT historias.id_Historia, historias.titulo, historias.subtitulo, historias.descripcion AS 'Desc_Historia', historias.enUso, medios.url, tipos.descripcion FROM historias INNER JOIN rel_historia ON historias.id_Historia=rel_historia.id_Historia INNER JOIN medios ON rel_historia.id_Medio=medios.id_Medio INNER JOIN tipos ON medios.id_Tipo = tipos.id_Tipo WHERE historias.titulo LIKE '".$titulo."';";


        $database = new Database();
        $resultado = $database->getConn()->query($query);
        
        $arrayMedios = array();
        
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $historia = new Historia();
            $historia->idHistoria=$row["id_Historia"];
            $historia->titulo=$row["titulo"];
            $historia->subtitulo=$row["subtitulo"];
            $historia->descripcion=$row["Desc_Historia"];
            $historia->enUso=$row["enUso"];
            array_push($arrayMedios, array("url"=> $row["url"], "tipo"=> $row["descripcion"]) );
        }
        $historia->medios = $arrayMedios;
        $paraDevolver = json_encode($historia);
        return $paraDevolver;

    }

    /**
     * Recibe la id y el título de un acto y busca por ellos en la base de datos 
     * @param integer $id string $titulo
     * @return Result Object
     */
    function buscarPorAmbos($id, $titulo) {
        $query = "SELECT historias.id_Historia, historias.titulo, historias.subtitulo, historias.descripcion AS 'Desc_Historia', historias.enUso, medios.url, tipos.descripcion FROM historias INNER JOIN rel_historia ON historias.id_Historia=rel_historia.id_Historia INNER JOIN medios ON rel_historia.id_Medio=medios.id_Medio INNER JOIN tipos ON medios.id_Tipo = tipos.id_Tipo WHERE historias.titulo LIKE '".$titulo."' AND historias.id_Historia LIKE ".$id.";";

        $database = new Database();
        $resultado = $database->getConn()->query($query);
        
        $arrayMedios = array();
        
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $historia = new Historia();
            $historia->idHistoria=$row["id_Historia"];
            $historia->titulo=$row["titulo"];
            $historia->subtitulo=$row["subtitulo"];
            $historia->descripcion=$row["Desc_Historia"];
            $historia->enUso=$row["enUso"];
            array_push($arrayMedios, array("url"=> $row["url"], "tipo"=> $row["descripcion"]) );
        }
        $historia->medios = $arrayMedios;
        $paraDevolver = json_encode($historia);
        return $paraDevolver;

    }

?>