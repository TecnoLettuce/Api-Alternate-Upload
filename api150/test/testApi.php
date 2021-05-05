<?php

//region imports
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//endregion
include_once '../config/database.php';



/**
 * Esta clase lanza de forma manual las consultas para testear todos los estados de la aplicación
 */

//region Variables

//Variables de uso común
$database = new Database();
$token = '23b51a4516a25d179d6c1afc80846560232dfbf0'; // token que se actualiza para hacer los test
echo "Token válido --> ".$token."\nToken inválido --> ";
$unauthorizedToken = successLogin("unauthorized", "unauthorized");
$UserTest = 'tester';
$UserTestPassword = 'tester';

// Variables para hacer fallar el login
$UserTestInvalidPassword = "Failure";

//region createUser
$createUsername = "createTest";
$createPassword = "createPassword";
$createMail = "createMail";
$createRol = "createRol";
//endregion

 
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

//region Update Acts
$nuevoTitulo = "programa1";
$viejoTitulo = "programa1";
$tituloFalla = "Titulohechoamalaostiaparafallar";
$nuevaFecha = "2050-10-10";
 
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
echo "\n\nAPI TESTING \n";
echo "-----------------------------------------------------------------------\n\n";

echo "LOGIN ENDPOINT \n";
echo "-----------------------------------------------------------------------\n";
echo "\nFailure login -> \n";
failureLogin($UserTest, $UserTestInvalidPassword);
echo "\n\nSuccess login --> \n";
$token = successLogin($UserTest, $UserTestPassword);
echo "\n\nNo data login --> \n";
noDataLogin($UserTest);

echo "\n\n\nCREATE USER ENDPOINT \n";
echo "-----------------------------------------------------------------------\n";
echo "\nSuccess Create User -> \n";
successCreateUser($createUsername, $createPassword, $createMail, $createRol, $token);
echo "\n\nNo Data Create User --> \n";
noDataCreateUser($createPassword, $createMail, $createRol, $token);
echo "\n\nInvalid Token Create user --> \n";
invalidTokenCreateUser($createUsername, $createPassword, $createMail, $createRol, $token);
echo "\n\nUnauthorized Create user --> \n";
unatuhorizedCreateUser($createUsername, $createPassword, $createMail, $createRol, $unauthorizedToken);


echo "\n\n\nINSERT ACT ENDPOINT \n";
echo "-----------------------------------------------------------------------\n";
echo "\nSuccess Insert Act -> \n";
successInsertAct($tituloSuccess, $fecha, $boolEnUso, $categoria, $token);
echo "\nAlready Created Act -> \n";
alreadyInsertedAct($tituloSuccess, $fecha, $boolEnUso, $categoria, $token);
echo "\n\nNo Data Insert Act --> \n";
noDataInsertAct($fecha,$boolEnUso,$categoria,$token);
echo "\n\nInvalid Token Insert Act --> \n";
invalidTokenInsertAct($tituloSuccess, $fecha, $boolEnUso, $categoria, $token);
echo "\n\nUnauthorized Insert Act --> \n";
unauthorizedInsertAct($tituloSuccess, $fecha,$enUso,$categoria, $unauthorizedToken);



echo "\n\n\DELETE ACT ENDPOINT \n";
echo "-----------------------------------------------------------------------\n";
echo "\nNot found Act -> \n";
notFoundDeleteAct(9999999, $token);
echo "\n\nNo Data Delete Act --> \n";
noDataDeleteAct($tituloSuccess, $token);
echo "\n\nInvalid Token Delete Act --> \n";
invalidTokenDeleteAct($tituloSuccess, $token);
echo "\n\nUnauthorized Delete Act --> \n";
unauthorizedDeleteAct($tituloSuccess ,$unauthorizedToken);
echo "\n\nSuccess Delete Act -> \n";
successDeleteAct($tituloSuccess, $token);


echo "\n\nUPDATE ACT ENDPOINT --> NO FUNCIONA PORQUE VAN POR PUT \n";
echo "-----------------------------------------------------------------------\n";
echo "\nNot found Update Act -> \n";
NotFoundUpdateAct($tituloFalla, $nuevoTitulo, $nuevaFecha, $boolEnUso, $token);
echo "\n\nNo Data Update Act --> \n";
noDataUpdateAct($viejoTitulo, $nuevoTitulo, $nuevaFecha, $boolEnUso, $token);
echo "\n\nInvalid Token Update Act --> \n";
invalidTokenUpdateAct($viejoTitulo, $nuevoTitulo, $nuevaFecha, $boolEnUso, $token);
echo "\n\nUnauthorized Update Act --> \n";
unauthorizedUpdateAct($viejoTitulo, $nuevoTitulo, $nuevaFecha, $boolEnUso, $unauthorizedToken);
echo "\n\nSuccess Update Act -> \n";
successUpdateAct($viejoTitulo, $nuevoTitulo, $nuevaFecha, $boolEnUso, $token);



echo "\n\nLIST ONE ACT ENDPOINT \n";
echo "-----------------------------------------------------------------------\n";
echo "\nNot found List One Act -> \n";
notFoundListOneAct();
echo "\n\nNo Data List One Act --> \n";
noDataListOneAct($viejoTitulo);
echo "\n\nSuccess List One Act -> \n";
successListOneAct($viejoTitulo);



























echo "\n\n\nLOGOUT ENDPOINT \n";
echo "-----------------------------------------------------------------------\n";
echo "\nSuccess Logout -> \n";
successLogout($token);
echo "\n\nNo Data Logout --> \n";
noDataLogout();
echo "\n\nInvalid Token Logout --> \n";
invalidTokenLogout($token);
echo "\n\nNo Session For token --> \n";
unatuhorizedLogout($unauthorizedToken);






//region functions

//region loginfunctions
function successLogin($username, $password){
    // Definimos los campos para el request
    $data = json_encode(array("username" => $username, "password"=> $password));
    // Definimos la URL
    $url = 'http://localhost/api150/api/auth/login.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
    $token = json_decode($result);
    $token = $token->token;
    return $token;
}

function failureLogin($username, $password){
    // Definimos los campos para el request
    $data = json_encode(array("username" => $username, "password"=> $password));
    // Definimos la URL
    $url = 'http://localhost/api150/api/auth/login.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function noDataLogin($username){
    // Definimos los campos para el request
    $data = json_encode(array("username" => $username));
    // Definimos la URL
    $url = 'http://localhost/api150/api/auth/login.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}
//endregion

//region createuserfunctions
function successCreateUser($createUsername, $createPassword, $createMail, $createRol, $token){
    // Definimos los campos para el request
    $data = json_encode(array("username" => $createUsername, "password"=> $createPassword, "mail"=> $createMail, "rol" => $createRol, "token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/auth/createUser.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function noDataCreateUser( $createPassword, $createMail, $createRol, $token){
    // Definimos los campos para el request
    $data = json_encode(array("password"=> $createPassword, "mail"=> $createMail, "rol" => $createRol, "token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/auth/createUser.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function invalidTokenCreateUser($createUsername, $createPassword, $createMail, $createRol, $token){
    // Definimos los campos para el request
    $badToken = "lsiufuiehfnjuñio";
   
    $data = json_encode(array("username" => $createUsername, "password"=> $createPassword, "mail"=> $createMail, "rol" => $createRol, "token" => $badToken ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/auth/createUser.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function unatuhorizedCreateUser($createUsername, $createPassword, $createMail, $createRol, $unauthorizedToken){

    // Definimos los campos para el request
    $data = json_encode(array("username" => $createUsername, "password"=> $createPassword, "mail"=> $createMail, "rol" => $createRol, "token" => $unauthorizedToken ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/auth/createUser.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}
//endregion

//region insertactfunctions
function successInsertAct($titulo, $fecha, $enUso, $categoria, $token){
    $database = new Database();
    $query = "DELETE FROM programas WHERE titulo LIKE '".$titulo."'";
    $stmt = $database->getConn()->prepare($query);                    
    $stmt->execute();

    // Definimos los campos para el request
    $data = json_encode(array("titulo" => $titulo, "fecha"=> $fecha, "enUso"=> $enUso, "categoria" => $categoria, "token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/insert.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function alreadyInsertedAct($titulo, $fecha, $enUso, $categoria, $token){

    // Definimos los campos para el request
    $data = json_encode(array("titulo" => $titulo, "fecha"=> $fecha, "enUso"=> $enUso, "categoria" => $categoria, "token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/insert.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function noDataInsertAct($fecha, $enUso, $categoria, $token){
    // Definimos los campos para el request
    $data = json_encode(array("fecha"=> $fecha, "enUso"=> $enUso, "categoria" => $categoria, "token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/insert.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function invalidTokenInsertAct($titulo, $fecha, $enUso, $categoria, $token){
    // Definimos los campos para el request
    $token = "lkdajsiefojkmvsoir";
    $data = json_encode(array("titulo" => $titulo, "fecha"=> $fecha, "enUso"=> $enUso, "categoria" => $categoria, "token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/insert.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function unauthorizedInsertAct($titulo, $fecha, $enUso, $categoria, $unauthorizedToken){
    // Definimos los campos para el request
    $data = json_encode(array("titulo" => $titulo, "fecha"=> $fecha, "enUso"=> $enUso, "categoria" => $categoria, "token" => $unauthorizedToken ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/insert.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

//endregion

//region deleteactfunctions
function successDeleteAct($titulo, $token){

    $database = new Database();
    $query = "SELECT id_Programa FROM programas WHERE titulo LIKE '".$titulo."'";
    // echo $query;
    $stmt = $database->getConn()->query($query);                    
    $idObtenido = null;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idObtenido = $row["id_Programa"];
    }

    // echo "idObtenido --> ".$idObtenido;
    // Definimos los campos para el request
    $data = json_encode(array("id" => $idObtenido, "token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/delete.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function notFoundDeleteAct($id, $token) {

    // echo "idObtenido --> ".$idObtenido;
    // Definimos los campos para el request
    $data = json_encode(array("id" => $id, "token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/delete.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function noDataDeleteAct($titulo, $token){

    $database = new Database();
    $query = "SELECT id_Programa FROM programas WHERE titulo LIKE '".$titulo."'";
    // echo $query;
    $stmt = $database->getConn()->query($query);                    
    $idObtenido = null;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idObtenido = $row["id_Programa"];
    }

    // echo "idObtenido --> ".$idObtenido;
    // Definimos los campos para el request
    $data = json_encode(array( "token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/delete.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function invalidTokenDeleteAct($titulo, $token){

    $database = new Database();
    $query = "SELECT id_Programa FROM programas WHERE titulo LIKE '".$titulo."'";
    // echo $query;
    $stmt = $database->getConn()->query($query);                    
    $idObtenido = null;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idObtenido = $row["id_Programa"];
    }

    $token = "lhsrkgljeik";
    // echo "idObtenido --> ".$idObtenido;
    // Definimos los campos para el request
    $data = json_encode(array("id" => $idObtenido, "token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/delete.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function unauthorizedDeleteAct($titulo, $token){

    $database = new Database();
    $query = "SELECT id_Programa FROM programas WHERE titulo LIKE '".$titulo."'";
    // echo $query;
    $stmt = $database->getConn()->query($query);                    
    $idObtenido = null;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idObtenido = $row["id_Programa"];
    }

    // echo "idObtenido --> ".$idObtenido;
    // Definimos los campos para el request
    $data = json_encode(array("id" => $idObtenido, "token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/delete.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

//endregion

//region updateactfunctions
function successUpdateAct($viejoTitulo, $nuevoTitulo, $nuevaFecha, $enUso, $token) {
    $database = new Database();
    $query = "SELECT id_Programa FROM programas WHERE titulo LIKE '".$viejoTitulo."'";
    // echo $query;
    $stmt = $database->getConn()->query($query);                    
    $idObtenido = null;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idObtenido = $row["id_Programa"];
    }

    // Definimos los campos para el request
    $data = json_encode(array("idPrograma" => $idObtenido, "nuevoTitulo" => $nuevoTitulo, "nuevaFecha"=> $nuevaFecha, "enUso"=> $enUso, "token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/update.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function NotFoundUpdateAct($tituloFalla, $nuevoTitulo, $nuevaFecha, $enUso, $token) {
    $database = new Database();
    $query = "SELECT id_Programa FROM programas WHERE titulo LIKE '".$tituloFalla."'";
    // echo $query;
    $stmt = $database->getConn()->query($query);                    
    $idObtenido = null;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idObtenido = $row["id_Programa"];
    }

    // Definimos los campos para el request
    $data = json_encode(array("idPrograma" => $idObtenido, "nuevoTitulo" => $nuevoTitulo, "nuevaFecha"=> $nuevaFecha, "enUso"=> $enUso, "token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/update.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}


function noDataUpdateAct($viejoTitulo, $nuevoTitulo, $nuevaFecha, $enUso, $token) {
    $database = new Database();
    $query = "SELECT id_Programa FROM programas WHERE titulo LIKE '".$viejoTitulo."'";
    // echo $query;
    $stmt = $database->getConn()->query($query);                    
    $idObtenido = null;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idObtenido = $row["id_Programa"];
    }

    // Definimos los campos para el request
    $data = json_encode(array("nuevoTitulo" => $nuevoTitulo, "nuevaFecha"=> $nuevaFecha, "enUso"=> $enUso, "token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/update.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function invalidTokenUpdateAct($viejoTitulo, $nuevoTitulo, $nuevaFecha, $enUso, $token) {
    $database = new Database();
    $query = "SELECT id_Programa FROM programas WHERE titulo LIKE '".$viejoTitulo."'";
    // echo $query;
    $stmt = $database->getConn()->query($query);                    
    $idObtenido = null;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idObtenido = $row["id_Programa"];
    }

    $token = "lsjhkguleiahfj";
    // Definimos los campos para el request
    $data = json_encode(array("idPrograma" => $idObtenido, "nuevoTitulo" => $nuevoTitulo, "nuevaFecha"=> $nuevaFecha, "enUso"=> $enUso, "token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/update.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function unauthorizedUpdateAct($viejoTitulo, $nuevoTitulo, $nuevaFecha, $enUso, $unauthorizedToken) {
    $database = new Database();
    $query = "SELECT id_Programa FROM programas WHERE titulo LIKE '".$viejoTitulo."'";
    // echo $query;
    $stmt = $database->getConn()->query($query);                    
    $idObtenido = null;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idObtenido = $row["id_Programa"];
    }

    // Definimos los campos para el request
    $data = json_encode(array("idPrograma" => $idObtenido, "nuevoTitulo" => $nuevoTitulo, "nuevaFecha"=> $nuevaFecha, "enUso"=> $enUso, "token" => $unauthorizedToken ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/update.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

//endregion

//region listoneactfunctions
function successListOneAct($viejoTitulo) {
    $database = new Database();
    $query = "SELECT id_Programa FROM programas WHERE titulo LIKE '".$viejoTitulo."'";
    // echo $query;
    $stmt = $database->getConn()->query($query);                    
    $idObtenido = null;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idObtenido = $row["id_Programa"];
    }

    // Definimos los campos para el request
    $data = array("idPrograma" => $idObtenido, "titulo"=>$viejoTitulo);
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/listOne.php';
    // Montamos la URL con el CGI
    $url = $url .'?'. http_build_query($data);
    // instanciamos el curl
    $ch = curl_init($url);
    // Configuramos el método por el que envía
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    // Configuramos la recepción de la respuesta
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function notFoundListOneAct() {

    // Definimos los campos para el request
    $data = array("idPrograma" => 99999999, "titulo"=>"NotFound");
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/listOne.php';
    // Montamos la URL con el CGI
    $url = $url .'?'. http_build_query($data);
    // instanciamos el curl
    $ch = curl_init($url);
    // Configuramos el método por el que envía
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    // Configuramos la recepción de la respuesta
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}


function noDataListOneAct($viejoTitulo) {
    $database = new Database();
    $query = "SELECT id_Programa FROM programas WHERE titulo LIKE '".$viejoTitulo."'";
    // echo $query;
    $stmt = $database->getConn()->query($query);                    
    $idObtenido = null;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idObtenido = $row["id_Programa"];
    }
    $data = array();
    // Definimos la URL
    $url = 'http://localhost/api150/api/acts/listOne.php';
    // Montamos la URL con el CGI
    $url = $url .'?'. http_build_query($data);
    // instanciamos el curl
    $ch = curl_init($url);
    // Configuramos el método por el que envía
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    // Configuramos la recepción de la respuesta
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}
//endregion







































//region logoutfunctions
function successLogout($token){
    // Definimos los campos para el request
    $data = json_encode(array("token" => $token));
    // Definimos la URL
    $url = 'http://localhost/api150/api/auth/logout.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

// NO FUNCIONA
function noDataLogout(){
    // Definimos los campos para el request
    $data = json_encode(array("token" => null));
    // Definimos la URL
    $url = 'http://localhost/api150/api/auth/logout.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    // echo $result; 
    echo "No funciona";
}

function invalidTokenLogout($token){
    // Definimos los campos para el request
    $token = "ksrjlhfjoñed´s";
    $data = json_encode(array("token" => $token ));
    // Definimos la URL
    $url = 'http://localhost/api150/api/auth/logout.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}

function unatuhorizedLogout($unauthorizedToken){

    // Definimos los campos para el request
    $data = json_encode(array("token" => $unauthorizedToken ));
    // Borramos la sesión por si la hubiera 
    $query = "DELETE FROM session WHERE token LIKE '".$unauthorizedToken."'";
    $database = new Database();
    $resultado = $database->getConn()->query($query);
    // Definimos la URL
    $url = 'http://localhost/api150/api/auth/logout.php';
    // instanciamos el curl
    $ch = curl_init($url);
    // Pasar el json al payload
    $payload = $data;
    // Configuramos el curl y le pasamos el payload
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // El content type tiene que ser application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Pedimos el reultado
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecutamos el envío
    $result = curl_exec($ch);
    // Cerramos el curl
    curl_close($ch);

    echo $result; 
}
//endregion




//endregion

?>