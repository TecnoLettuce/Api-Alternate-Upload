<?php
    /**
     * Clase que contiene todos los parametros del usuario
     * y sus métodos
     */
    class User {
        //region Atributos base de datos de la clase user
        private $conn;
        private $table_name = "user";
        //endregion

        //region Atributos propios de la clase user
        private $idUser;
        private $userName;
        private $password;
        private $mail;
        private $idRol;
        //endregion

        //region Constructor
        public function __construct($db) {
            $this->conn = $db;
        }
        //endregion

        /**
         * TODO
         * Metodo read()
         * @args -> null 
         * Hace la consulta contra la base de datos y devuelve un
         * Statement con los resultados que ha conseguido
         */
        function read(){
            
        }
    
        /**
         * TODO
         * Metodo create()
         * @args -> null
         * Crea un usuario con los datos que se recogen del post
         * return boolean
         */
    
        function create()   {
           
        } // Salida del metodo create 
    
        /**
         * TODO
         * Metodo readOne()
         * @args -> null
         * Lee un unico user atendiendo a su ID
         */
        function readOne()   {
           
        } // salida del metodo leer uno
    
        /**
         * TODO
         * Metodo update()
         * @args -> null
         * Actualiza los valores en la base de datos
         * en base a un objeto que se genera
         * @Return boolean
         */
        function update() {
            
        } // Salida del metodo actualizar
    
        /**
         * TODO
         * Metodo delete()
         * @args -> null
         * Elimina el producto con la id definida
         * @Return boolean
         */
        function delete() {
    
        } // Salida del metodo delete
    
        /**
         * TODO
         * Metodo search()
         * @args -> null
         * Busca elementos por palabras clave (Muy util)
         * @Return Statement
         */
        function search($keywords){
      
        }

    } // Salida de la clase
?>