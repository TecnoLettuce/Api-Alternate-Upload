<?php 
    include_once '../config/database.php';
    include_once '../util/act.php';
    include_once '../util/ambiente.php';
    include_once '../util/greetings.php';
    include_once '../util/commonFunctions.php';
    include_once '../util/historia.php';
    include_once '../util/hymn.php';
    include_once '../util/logger.php';
    include_once '../util/material.php';
    include_once '../util/phrase.php';
    include_once '../util/pray.php';
    include_once '../util/uploadFilesByURL.php';
    include_once '../util/visit.php';
    include_once '../objects/session.php';

    class Dao {

        public function __construct() {
            $database = new Database();
            $cf = new CommonFunctions();
            $logger = new Logger();
            $cfu = new UploadCommonFunctions();
        }

        //region Actos
        // Métodos para el endpoint de insertar actos 
        function insertarActo ($titulo, $fecha, $boolEnUso, $categoria) {
            $database = new Database();
            $query = "INSERT INTO programas (id_Programa, titulo, fecha, enUso, id_Categoria) VALUES (null,'".$titulo."','".$fecha."',".$boolEnUso.", '".$categoria."');";
            // echo "La consulta para insertar un programa es ".$query;
            $stmt = $database->getConn()->prepare($query);
            // echo "La consulta para insertar el programa es ".$query;
            $stmt->execute();
        }

        // Métodos para el endpoint de borrar actos
        function borrarActo($idRecibida) {
            $database = new Database();
            $query = "DELETE FROM programas WHERE id_Programa like ".$idRecibida.";";
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();
        }

        // Métodos para listar actos
        function listarActos() {
            $database = new Database();
            // No tiene que recibir parámetros es solo la consulta pelada
            $query = "SELECT * FROM programas;";
            $resultado = $database->getConn()->query($query);
            return $resultado;
        }

        // Listar acto por id + titulo
        function listarUnActoPorIdyTitulo($id, $titulo) {
            $query = "SELECT * FROM programas WHERE id_Programa LIKE ".$id." AND titulo LIKE '".$titulo."';";
            $database = new Database();
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $programa = new Programa();
                $programa->id=$row["id_Programa"];
                $programa->titulo=$row["titulo"];
                $programa->fecha=$row["fecha"];
                $programa->enUso=$row["enUso"];
                $programa->categoria=$row["id_Categoria"];
                array_push($arr, $programa);
            }
            $paraDevolver = json_encode($arr);
            return $paraDevolver;
        }
        // Listar acto por id
        function listarUnActoPorId($id) {
            $query = "SELECT * FROM programas WHERE id_Programa LIKE ".$id.";";
            $database = new Database();
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $programa = new Programa();
                $programa->id=$row["id_Programa"];
                $programa->titulo=$row["titulo"];
                $programa->fecha=$row["fecha"];
                $programa->enUso=$row["enUso"];
                $programa->categoria=$row["id_Categoria"];
                array_push($arr, $programa);
            }
            $paraDevolver = json_encode($arr);
            return $paraDevolver;
            
        }
        // Listar acto por titulo
        function listarUnActoPorTitulo($titulo) {
            $query = "SELECT * FROM programas WHERE titulo LIKE '".$titulo."';";
            $database = new Database();
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $programa = new Programa();
                $programa->id=$row["id_Programa"];
                $programa->titulo=$row["titulo"];
                $programa->fecha=$row["fecha"];
                $programa->enUso=$row["enUso"];
                $programa->categoria=$row["id_Categoria"];
                array_push($arr, $programa);
            }
            $paraDevolver = json_encode($arr);
            return $paraDevolver;
            
        }

        // Métodos para el endpoint de update Actos 
        function actualizarActo ($nuevoTitulo, $nuevaFecha, $boolEnUso, $idPrograma) {
            $database = new Database();
            $query = "UPDATE programas SET titulo = '".$nuevoTitulo."', fecha = '".$nuevaFecha."', enUso = ".$boolEnUso." WHERE id_Programa LIKE ".$idPrograma.";";
            echo "consulta > ".$query;
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();
        }
        
        //endregion

        //region Saludos
        // Métodos para el endpoint de insertar saludos
        function insertarSaludo($titulo, $descripcion, $texto, $boolEnUso){
            $database = new Database();
            $query = "INSERT INTO saludos (id_Saludo, titulo, descripcion, texto, enUso) VALUES (null,'".$titulo."','".$descripcion."', '".$texto."', ".$boolEnUso.");";
            // echo "La consulta para insertar una historia es ".$query;
            $stmt = $database->getConn()->prepare($query);
            // echo "La consulta para insertar la historia es ".$query;
            $stmt->execute();
        }

        // Métodos para el endpoint de borrar saludos
        function borrarSaludo($idRecibida){
            $database  = new Database();
            $query = "DELETE FROM saludos WHERE id_Saludo like ".$idRecibida.";";
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();
        }

        // Métodos para el endpoint de listar saludos
        function listarSaludos(){
            $database = new Database();
            $query = "SELECT * FROM saludos;";
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
            return $arr;
        }

        // Métodos para el endpoint de listar un saludo
        function listarUnSaludoPorId($id) {
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

        function listarUnSaludoPorIdyTitulo($id, $titulo) {
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

        function listarUnSaludoPorTitulo($titulo) {
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

        // Métodos para el endpoint de update saludos
        function actualizarSaludo($nuevoTitulo, $nuevaDescripcion, $nuevoTexto, $boolEnUso, $idSaludo) {
            $database = new Database();
            $query = "UPDATE saludos SET titulo = '".$nuevoTitulo."', descripcion = '".$nuevaDescripcion."', texto = '".$nuevoTexto."', enUso = ".$boolEnUso." WHERE id_Saludo LIKE ".$idSaludo.";";
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();
        }
        //endregion

        //region himnos
        public function insertarHimno($tituloRecibido, $letraRecibida, $boolEnUso) {
            $database = new Database();
            // el himno no existe 
            $query = "INSERT INTO himnos (id_Himno, titulo, letra, enUso) VALUES (null,'".$tituloRecibido."', '".$letraRecibida."', ".$boolEnUso.");";
            // echo "La consulta para insertar un ambiente es ".$query;
            $stmt = $database->getConn()->prepare($query);
                
            $stmt->execute();

        }

        public function borrarHimno($idRecibida) {
            $database = new Database();
            $query = "DELETE FROM himnos WHERE id_Himno like ".$idRecibida.";";
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();
        }

        public function listarHimnos() {
            $database = new Database();
            $query = "SELECT * FROM himnos;";
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $himno = new Himno();
                $himno->id=$row["id_Himno"];
                $himno->titulo=$row["titulo"];
                $himno->letra=$row["letra"];
                $himno->enUso=$row["enUso"];
                array_push($arr, $himno);
            }
            return $arr;
        }

        public function listarUnHimnoPorId($id) {
            $query = "SELECT * FROM himnos WHERE id_Himno LIKE ".$id.";";
            $database = new Database();
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $himno = new Himno();
                $himno->id=$row["id_Himno"];
                $himno->titulo=$row["titulo"];
                $himno->letra=$row["letra"];
                $himno->enUso=$row["enUso"];
                array_push($arr, $himno);
            }
            $paraDevolver = json_encode($arr);
            return $paraDevolver;

        }

        public function listarUnHimnoPorIdyTitulo($id, $titulo){
            $query = "SELECT * FROM himnos WHERE id_Himno LIKE ".$id." AND titulo LIKE '".$titulo."';";
            $database = new Database();
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $himno = new Himno();
                $himno->id=$row["id_Himno"];
                $himno->titulo=$row["titulo"];
                $himno->letra=$row["letra"];
                $himno->enUso=$row["enUso"];

                array_push($arr, $himno);
            }
            $paraDevolver = json_encode($arr);
            return $paraDevolver;
        }

        public function listarUnHimnoPorTitulo($titulo){
            $query = "SELECT * FROM himnos WHERE titulo LIKE '".$titulo."';";
            $database = new Database();
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $himno = new Himno();
                $himno->id=$row["id_Himno"];
                $himno->titulo=$row["titulo"];
                $himno->letra=$row["letra"];
                $himno->enUso=$row["enUso"];

                array_push($arr, $himno);
            }
            $paraDevolver = json_encode($arr);
            return $paraDevolver;
        }

        public function actualizarHimno($nuevoTitulo, $nuevaLetra, $boolEnUso, $idHimno) {
            $database = new Database();
            $query = "UPDATE himnos SET titulo = '".$nuevoTitulo."', letra= '".$nuevaLetra."', enUso = ".$boolEnUso." WHERE id_Himno LIKE ".$idHimno.";";
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();

        }
        //endregion

        //region Frases
        public function insertarFrase ($texto, $fecha, $boolEnUso) {
            $database = new Database();
            $query = "INSERT INTO frase_inicio (id_Frase, texto, fecha, enUso) VALUES (null,'".$texto."','".$fecha."', ".$boolEnUso.");"; 
            // echo "La consulta para insertar un programa es ".$query;
            $stmt = $database->getConn()->prepare($query);
            // echo "La consulta para insertar el programa es ".$query;    
            $stmt->execute();
        }

        public function borrarFrase ($id){
            $database = new Database();
            $query = "DELETE FROM frase_inicio WHERE id_Frase like ".$id.";";
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();
        }

        public function listarFrases () {
            $database = new Database();
            $query = "SELECT * FROM frase_inicio;";
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $frase = new Frase();
                $frase->id=$row["id_Frase"];
                $frase->texto=$row["texto"];
                $frase->fecha=$row["fecha"];
                $frase->enUso=$row["enUso"];
                array_push($arr, $frase);
            }
            return $arr;
        }   

        public function listarUnaFrasePorId ($id){
            $query = "SELECT * FROM frase_inicio WHERE id_Frase LIKE ".$id.";";
            $database = new Database();
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $frase = new Frase();
                $frase->id=$row["id_Frase"];
                $frase->texto=$row["texto"];
                $frase->fecha=$row["fecha"];
                $frase->enUso=$row["enUso"];
                array_push($arr, $frase);
            }
            $paraDevolver = json_encode($arr);
            return $paraDevolver;
        }

        public function listarUnaFrasePorIdyFecha($id, $fecha){
            $query = "SELECT * FROM frase_inicio WHERE id_Frase LIKE ".$id." AND fecha LIKE '".$fecha."';";
            $database = new Database();
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $frase = new Frase();
                $frase->id=$row["id_Frase"];
                $frase->texto=$row["texto"];
                $frase->fecha=$row["fecha"];
                $frase->enUso=$row["enUso"];
                array_push($arr, $frase);
            }
            $paraDevolver = json_encode($arr);
            return $paraDevolver;
        }

        public function listarUnaFrasePorFecha($fecha){
            $query = "SELECT * FROM frase_inicio WHERE fecha LIKE '".$fecha."';";
            $database = new Database();
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $frase = new Frase();
                $frase->id=$row["id_Frase"];
                $frase->texto=$row["texto"];
                $frase->fecha=$row["fecha"];
                $frase->enUso=$row["enUso"];
                array_push($arr, $frase);
            }
            $paraDevolver = json_encode($arr);
            return $paraDevolver;
        }

        public function actualizarFrase($nuevoTexto, $nuevaFecha, $boolEnUso, $idFrase) {
            $database = new Database();
            $query = "UPDATE frase_inicio SET texto = '".$nuevoTexto."',fecha = '".$nuevaFecha."', enUso=".$boolEnUso." WHERE id_Frase LIKE ".$idFrase.";";
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();
        }
        
        //endregion

        //region Ambientes
        public function insertarAmbiente($tituloAmbienteRecibido, $descripcionAmbienteRecibido, $boolEnUso) {
            $database = new Database();
            $query = "INSERT INTO ambiente (id_Ambiente, titulo, descripcion, enUso) VALUES (null,'".$tituloAmbienteRecibido."', '".$descripcionAmbienteRecibido."', ".$boolEnUso.");";
            // echo "La consulta para insertar un ambiente es ".$query;
            $stmt = $database->getConn()->prepare($query);
                        
            $stmt->execute();
        }

        public function borrarAmbiente($idRecibida) {
            $database = new Database();
            $query = "DELETE FROM ambiente WHERE id_Ambiente like ".$idRecibida.";";
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();
        }

        public function listarAmbientes() {
            $database = new Database();
            $query = "SELECT * FROM ambiente;";
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $ambiente = new Ambiente();
                $ambiente->id=$row["id_Ambiente"];
                $ambiente->titulo=$row["titulo"];
                $ambiente->descripcion=$row["descripcion"];
                $ambiente->enUso=$row["enUso"];
                array_push($arr, $ambiente);
            }
            return $arr;
        }

        public function ListarUnAmbientePorId($id) {
            $query = "SELECT * FROM ambiente WHERE id_Ambiente LIKE ".$id.";";
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

        public function ListarUnAmbientePorIdyTitulo($id, $titulo) {
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

        public function ListarUnAmbientePorTitulo($titulo) {
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
        
        public function actualizarAmbiente($nuevoTitulo, $boolEnUso, $idAmbiente) {
            
            $database = new Database();
            $query = "UPDATE ambiente SET titulo = '".$nuevoTitulo."', enUso = ".$boolEnUso." WHERE id_Ambiente LIKE ".$idAmbiente.";";
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();
        }
        //endregion

        //region Oraciones
        public function insertarOracion($titulo, $texto, $boolEnUso) {

            $database = new Database();
            $query = "INSERT INTO oraciones (id_Oracion, titulo, texto, enUso) VALUES (null,'".$titulo."','".$texto."', ".$boolEnUso.");";
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();
        }

        public function borrarOracion($idRecibida){
            $database = new Database();
            $query = "DELETE FROM oraciones WHERE id_Oracion like ".$idRecibida.";";
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();
        }

        public function listarOracion(){
            $database = new Database();
            $query = "SELECT * FROM oraciones;";
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $pray = new Pray();
                $pray->id=$row["id_Oracion"];
                $pray->titulo=$row["titulo"];
                $pray->texto=$row["texto"];
                $pray->enUso=$row["enUso"];
                array_push($arr, $pray);
            }
            return $arr;
        }

        public function listarUnaOracionPorId($id){
            $query = "SELECT * FROM oraciones WHERE id_Oracion LIKE ".$id.";";
            $database = new Database();
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $pray = new Pray();
                $pray->id=$row["id_Oracion"];
                $pray->titulo=$row["titulo"];
                $pray->texto=$row["texto"];
                $pray->enUso=$row["enUso"];
                array_push($arr, $pray);
            }
            $paraDevolver = json_encode($arr);
            return $paraDevolver;

        }

        public function listarUnaOracionPorIdyTitulo($id, $titulo){
            $query = "SELECT * FROM oraciones WHERE id_Oracion LIKE ".$id." AND titulo LIKE '".$titulo."';";
            $database = new Database();
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $pray = new Pray();
                $pray->id=$row["id_Oracion"];
                $pray->titulo=$row["titulo"];
                $pray->texto=$row["texto"];
                $pray->enUso=$row["enUso"];
                array_push($arr, $pray);
            }
            $paraDevolver = json_encode($arr);
            return $paraDevolver;

        }

        public function listarUnaOracionPorTitulo($titulo){
            $query = "SELECT * FROM oraciones WHERE titulo LIKE '".$titulo."';";
            $database = new Database();
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $pray = new Pray();
                $pray->id=$row["id_Oracion"];
                $pray->titulo=$row["titulo"];
                $pray->texto=$row["texto"];
                $pray->enUso=$row["enUso"];
                array_push($arr, $pray);
            }
            $paraDevolver = json_encode($arr);
            return $paraDevolver;

        }

        public function actualizarOracion($nuevoTitulo, $nuevoTexto, $boolEnUso, $idOracion){
                        
            $database = new Database();
            $query = "UPDATE oraciones SET titulo = '".$nuevoTitulo."',texto = '".$nuevoTexto."', enUso = ".$boolEnUso." WHERE id_Oracion LIKE ".$idOracion.";";
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();
        }
        //endregion

        //region historias
        /**
         * FALTA ESTA ADAPTACIÓN POR HACER, A LA ESPERA DE QUE SE CONFIRME EL FUNCIONAMIENTO
         * EN PRODUCCIÓN.
         */
        //endregion

        //region visitas

        public function insertarVisita ($tituloVisita) {
            $database = new Database();
            $query = "INSERT INTO visitas (id_Visita, titulo) VALUES (null,'".$tituloVisita."');";
            // echo "La consulta para insertar un visita es ".$query;
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();
        }

        public function borrarVisita ($idRecibida) {
            $database = new Database();
            $query = "DELETE FROM visitas WHERE id_Visita like ".$idRecibida.";";
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();
            
        }

        public function listarVisita () {
            $database = new Database();
            $query = "SELECT * FROM visitas;";
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $visita = new Visit();
                $visita->id=$row["id_Visita"];
                $visita->titulo=$row["titulo"];
                array_push($arr, $visita);
            }
            return $arr;
        }

        public function listarVisitaPorId ($id) {
            $query = "SELECT * FROM visitas WHERE id_Visita LIKE ".$id.";";
            $database = new Database();
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $visita = new Visit();
                $visita->id=$row["id_Visita"];
                $visita->titulo=$row["titulo"];
                
                array_push($arr, $visita);
            }
            $paraDevolver = json_encode($arr);
            return $paraDevolver;
        }

        public function listarVisitaPorTitulo ($titulo) {
            $query = "SELECT * FROM visitas WHERE titulo LIKE '".$titulo."';";
            $database = new Database();
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $visita = new Visit();
                $visita->id=$row["id_Visita"];
                $visita->titulo=$row["titulo"];
                
                array_push($arr, $visita);
            }
            $paraDevolver = json_encode($arr);
            return $paraDevolver;
        }

        public function listarVisitaPorIdyTitulo ($id, $titulo) {
            $query = "SELECT * FROM visitas WHERE id_Visita LIKE ".$id." AND titulo LIKE '".$titulo."';";
            $database = new Database();
            $resultado = $database->getConn()->query($query);
            
            $arr = array();
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $visita = new Visit();
                $visita->id=$row["id_Visita"];
                $visita->titulo=$row["titulo"];
                
                array_push($arr, $visita);
            }
            $paraDevolver = json_encode($arr);
            return $paraDevolver;
        }

        public function actualizarVisita ($nuevoTitulo, $idVisita) {
            $database = new Database();
            $query = "UPDATE visitas SET titulo = '".$nuevoTitulo."' WHERE id_Visita LIKE ".$idVisita.";";
            $stmt = $database->getConn()->prepare($query);
            $stmt->execute();
        }
        //endregion
    
    } // Salida de la clase
    

?>