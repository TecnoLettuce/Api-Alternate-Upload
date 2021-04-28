<?php

/**
 * Clase que contiene todos los parametros de una sesion
 * y sus metodos
 */
include_once '../../config/database.php';
class Session {

    //region Atributos base de datos de la clase session
    private $db;
    private $conn;
  
    //endregion

    //region Atributos propios de la clase session
    public $idSession;
    public $idUser;
    public $token;
    public $expireDate;
    //endregion
    

    //region constructor
    public function __construct($db)
    {
        $this->conn = $db;
        echo "LOG > Class Session > Se ha creado la conexión sin incidentes";
    }
    //endregion    

    /**
     * TODO
     * Metodo read()
     * @args -> null 
     * Hace la consulta contra la base de datos y devuelve un
     * Statement con los resultados que ha conseguido
     */
    function read() {
        // Creamos la consulta con los valores que tenemos actualmente
        /**
         * La consulta SQL es esta
         * INSERT INTO table_name (column1, column2, column3, ...) VALUES (value1, value2, value3, ...);
         */
        $query = "INSERT INTO" . $this->table_name . "(idSession, idUser, token, expirateDate) VALUES (".$this->idSession.",".$this->idUser.",".$this->token.",".$this->expireDate.");";
        echo "LOG > Class Session > Method Create > esta es la consulta que estoy enviando al SQL --> ".$query;
        // declarar la query
        $stmt = $this->conn->prepare($query);

        // ejecutar consulta
        if ($stmt->execute()) {
            echo "LOG > Class Session > Method execute > Consulta correcta";
            return true;
        }else {
            echo "LOG > Class Session > Method execute > Consulta correcta";
            return false;            
        }
    }

    /**
     * TODO
     * Metodo create()
     * @args -> null
     * Crea una sesion con los datos que se recogen del post
     * return boolean
     */

    function create() {
        
    } // Salida del metodo create 


    /**
     * TODO
     * Metodo search()
     * @args -> null
     * Busca una sesion por elementos clave (Muy util)
     * @Return Statement
     */
    function search($keywords)
    {

    }

    /**
     * TODO
     * Metodo readOne()
     * @args -> null
     * Lee una unica sesion atendiendo a su ID
     */
    function readOne()
    {

    } // salida del metodo leer uno

    /**
     * TODO
     * Metodo delete()
     * @args -> null
     * Elimina la sesion con la id definida
     * @Return boolean
     */
    function delete()
    {

    } // Salida del metodo delete



}
