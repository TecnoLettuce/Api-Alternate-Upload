<?php

    class Logger {

        public function __construct() {
        }

        // No hay permisos
        function not_permission() {
            echo json_encode("status : 401, message : no tiene permisos para realizar esta operación");
        }

        // Token invalido
        function invalid_token() {
            echo json_encode("status : 403, message : token no valido");
        }

        // Sesión excedida
        function expired_session() {
            echo json_encode("status : 401, message : Tiempo de sesión excedido");
        }

        // Elemento ya existe
        function already_exists($element) {
            echo json_encode(array("status : 406, message : ".$element." ya existe" ));
        }

        // Fatal error
        function fatal_error($message) {
            echo json_encode("status : Fatal error, message : ".$message."");
        }

        // Faltan datos
        function incomplete_data() {
            echo json_encode("status : 400, message : Faltan uno o más datos");
        }

        // Elemento creado
        function created_element() {
            return "status : 200, message : Elemento creado";
        }

    }
    

?>