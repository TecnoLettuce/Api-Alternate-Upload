<?php

//region imports
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//endregion

// NO COMMITEADO



/**
 * Esta clase lanza de forma manual las consultas para testear todos los estados de la aplicación
 */

//region Variables

//Variables de uso común
$token = '23b51a4516a25d179d6c1afc80846560232dfbf0'; // token que se actualiza para hacer los test
$UserTest = 'tester';
$UserTestPassword = 'tester';

 
//region Insert Acts
$tituloSuccess = "UnitTestApi";
$fecha = "2021-12-1";
$boolEnUso = 1;
$categoria = 1;
//endregion

//region Delete Acts
$titulo = "UnitTestApi";
$fecha = "2021-12-1";
$boolEnUso = 1;
$categoria = 1;
//endregion

 
//endregion



/**
 * Test de cada endpoint -->
 *      - Insertar
 *          - Insertar con exito
 *          - Ya existe
 *          - faltan datos 
 *          - sesión expirada
 *          - Sin permisos
 *          - token inválido
 *      
 *      - Borrar
 *          - Borrar con éxito 
 *          - Elemento no existe 
 *          - Faltan datos 
 *          - Sesión expirada 
 *          - Sin permisos
 *          - Token inválido 
 *      
 *      - Update 
 *          - Actualizar con éxito
 *          - Elemento no existe 
 *          - Faltan datos 
 *          - Sesión expirada 
 *          - Sin permisos 
 *          - Token inválido 
 * 
 *      - List
 *          - Existen registros  
 *          - No exiten registros 
 *  
 *      - ListOne       
 *          - Listado con éxito id
 *          - Listado con éxito titulo
 *          - Falla búsqueda por ID 
 *          - Falla búsqueda por Titulo
 *          - Faltan ambos datos 
 *          - No existe valor 
 * 
 */

// Lo primero es loguearse y actualizar la sesión del usuario de testing
login($UserTest, $UserTestPassword);




function login($username, $password){
    // Definimos los campos para el request
    $data = json_encode(array("username" => $username, "password"=> $password));
    echo $data;
    

    $url = 'http://localhost/api150/api/auth/login.php';

    // Create a new cURL resource
    $ch = curl_init($url);

    // Setup request to send json via POST
    $payload = json_encode(array("user" => $data));

    // Attach encoded JSON string to the POST fields
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    // Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    // Return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the POST request
    $result = curl_exec($ch);

    echo $result;

    // Close cURL resource
    curl_close($ch);
        
}

?>