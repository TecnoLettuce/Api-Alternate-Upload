<?php

    class Logger {

        public function __construct() {
        }

        // No hay permisos
        function not_permission() {
            echo json_encode(array("status" => 403, "message" => "no tiene permisos para realizar esta operación"));
        }

        // Token invalido
        function invalid_token() {
            echo json_encode(array("status" => 403, "meesage" => "token no valido"));
        }

        // Sesión excedida
        function expired_session() {
            echo json_encode(array("status" => 401, "message" => "Tiempo de sesión excedido"));
        }

        // Elemento ya existe
        function already_exists($element) {
            echo json_encode(array("status" => 406, "message" => $element." ya existe" ));
        }

        // Fatal error
        function fatal_error($message) {
            echo json_encode(array("status" => "Fatal error", "message" => $message));
        }

        // Faltan datos
        function incomplete_data() {
            echo json_encode(array("status" => 400, "message" => "Faltan uno o más datos" ));
        }

        // Elemento creado
        function created_element() {
            echo json_encode(array("status" => 201, "message" => "Elemento creado"));
        }

        // Elemento borrado
        function deleted_element() {
            echo json_encode(array("status" => 200, "message" => "Elemento borrado"));
        }

        // Elemento actualizado
        function updated_element() {
            echo json_encode(array("status" => 200, "message" => "Elemento actualizado"));
        }

        // Elemento a borrar no existe
        function not_exists($element) {
            echo json_encode(array("status" => 406, "message" => $element." no existe" ));
        }

    }

    /**
     * Basados en esta página --> https://developer.mozilla.org/es/docs/Web/HTTP/Status
     */
    

?>