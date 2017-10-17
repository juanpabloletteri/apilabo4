<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once './vendor/autoload.php';
require_once './clases/AccesoDatos.php';
require_once './clases/persona.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

/*

¡La primera línea es la más importante! A su vez en el modo de 
desarrollo para obtener información sobre los errores
 (sin él, Slim por lo menos registrar los errores por lo que si está utilizando
  el construido en PHP webserver, entonces usted verá en la salida de la consola 
  que es útil).

  La segunda línea permite al servidor web establecer el encabezado Content-Length, 
  lo que hace que Slim se comporte de manera más predecible.
*/

$app = new \Slim\App(["settings" => $config]);

//require_once "saludo.php";

///////////////////////////////////
$app->post('/agregarmaterial', function (Request $request, Response $response) {  
    $nombre = $request->getParam("nombre");
    $mail = $request->getParam("mail");
    $sexo = $request->getParam("sexo");
    $password = $request->getParam("password");
    $response = persona::AgregarMaterial($nombre, $mail, $sexo, $password);
    return $response;
});
///////////////////////////////////
$app->get('/tablamateriales', function (Request $request, Response $response) {  
    $response = persona::traerTodos();
    return $response;
});
//////////////////////////////////////////////
$app->post('/eliminarmaterial', function (Request $request, Response $response) {  
    $id = $request->getParam("id");
    $response = persona::EliminarMaterial($id);
    return $response;
});
///////////////////////////////////
$app->get('[/]', function (Request $request, Response $response) {
    $response->getBody()->write("GET => Bienvenido!!! ,a SlimFramework");
    return $response;
});

$app->post('[/]', function (Request $request, Response $response) {
    $response->getBody()->write("POST => Bienvenido!!! ,a SlimFramework");
    return $response;
});

$app->put('[/]', function (Request $request, Response $response) {
    $response->getBody()->write("PUT => Bienvenido!!! ,a SlimFramework");
    return $response;
});

$app->delete('[/]', function (Request $request, Response $response) {
    $response->getBody()->write(" DELETE => Bienvenido!!! ,a SlimFramework");
    return $response;
});


/*LLAMADA A METODOS DE INSTANCIA DE UNA CLASE*/
$app->group('/persona', function () {

    $this->get('/', \persona::class . ':traerTodos');
    $this->get('/{id}', \persona::class . ':traerUno');
    $this->delete('/{id}', \personacd::class . ':BorrarUno');
    $this->put('/', \persona::class . ':ModificarUno');
//se puede tener funciones definidas
/*SUBIDA DE ARCHIVO*/
    $this->post('/', function (Request $request, Response $response) {
  
    
        $ArrayDeParametros = $request->getParsedBody();
        //var_dump($ArrayDeParametros);
        $nombre= $ArrayDeParametros['nombre'];
        $mail= $ArrayDeParametros['mail'];
        $sexo= $ArrayDeParametros['sexo'];
        $password= $ArrayDeParametros['password'];

        $micd = new persona();
        $micd->nombre=$nombre;
        $micd->mail=$mail;
        $micd->sexo=$sexo;
        $micd->password=$password;
        $micd->InsertarElCdParametros();

        /*
        $archivos = $request->getUploadedFiles();
        $destino="./fotos/";
        //var_dump($archivos);
        //var_dump($archivos['foto']);

        $nombreAnterior=$archivos['foto']->getClientFilename();
        $extension= explode(".", $nombreAnterior)  ;
        //var_dump($nombreAnterior);
        $extension=array_reverse($extension);

        $archivos['foto']->moveTo($destino.$titulo.".".$extension[0]);
        $response->getBody()->write("cd");
        */
        return $response;
    });
});











$app->run();
