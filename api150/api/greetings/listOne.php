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

    //Creación de la base de datos 
    $database = new Database();
    // Declaración de commonFunctions
    $cf = new CommonFunctions();

    // Recibe el titulo o el ID de un saludo y lo busca en la base de datos 
    $id = htmlspecialchars($_GET["idSaludo"]);
    $titulo = htmlspecialchars($_GET["titulo"]);

    //echo "Valores recogidos > id -> ".$id." | titulo -> ".$titulo; 

    if (!empty($id) && !empty($titulo)) {
        //echo "Estoy en la rama de las 2 recogidas";
        // Están ambos valores
        echo buscarPorAmbos($id, $titulo);
    } elseif (!empty($id) && empty($titulo)) {
        //echo "Estoy en la rama de solo la id";

        // Está solo el id
        echo buscarPorId($id);
    } elseif (empty($id) && !empty($titulo)) {
        //echo "Estoy en la rama de solo titulo";

        // Está solo el titulo
        echo buscarPorTitulo($titulo);
    } else {
        // No hay ninguno
        echo json_encode(" error : 400, message : Faltan uno o más datos");
    }

   
    /**
     * Recibe la id de un acto y busca por ella en la base de datos 
     * @param integer $id
     * @return Result Object
     */
    function buscarPorId($id) {
        $query = "SELECT * FROM saludos WHERE id_Saludo LIKE ".$id.";";
        $database = new Database();
        $resultado = $database->getConn()->query($query);
        
        $arr = array();
        
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $saludo = new Saludo();
            $saludo->id=$row["id_Saludo"];
            $saludo->titulo=$row["titulo"];
            $saludo->descripcion=$row["descripcion"];
            $saludo->texto=$row["texto"];
            $saludo->enUso=$row["enUso"];
            array_push($arr, $saludo);
        }
        $paraDevolver = json_encode($arr);
        return $paraDevolver;
    }
    /**
     * Recibe el titulo de un acto y busca por el en la base de datos 
     * @param string $titulo
     * @return Result Object
     */
    function buscarPorTitulo($titulo) {
        $query = "SELECT * FROM saludos WHERE titulo LIKE '".$titulo."';";
        $database = new Database();
        $resultado = $database->getConn()->query($query);
        
        $arr = array();
        
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $saludo = new Saludo();
            $saludo->id=$row["id_Saludo"];
            $saludo->titulo=$row["titulo"];
            $saludo->descripcion=$row["descripcion"];
            $saludo->texto=$row["texto"];
            $saludo->enUso=$row["enUso"];
            array_push($arr, $saludo);
        }
        $paraDevolver = json_encode($arr);
        return $paraDevolver;

    }

    /**
     * Recibe la id y el título de un acto y busca por ellos en la base de datos 
     * @param integer $id string $titulo
     * @return Result Object
     */
    function buscarPorAmbos($id, $titulo) {
        $query = "SELECT * FROM saludos WHERE id_Saludo LIKE ".$id." AND titulo LIKE '".$titulo."';";
        $database = new Database();
        $resultado = $database->getConn()->query($query);
        
        $arr = array();
        
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $saludo = new Saludo();
            $saludo->id=$row["id_Saludo"];
            $saludo->titulo=$row["titulo"];
            $saludo->descripcion=$row["descripcion"];
            $saludo->texto=$row["texto"];
            $saludo->enUso=$row["enUso"];
            array_push($arr, $saludo);
        }
        $paraDevolver = json_encode($arr);
        return $paraDevolver;

    }

?>