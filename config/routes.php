<?php

//aca van las rutas en un group

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UsuariosController;
use App\Controllers\MateriasController;
use App\Middleware\ValidarDatosMiddleware;
use App\Middleware\ValidarTipoMiddleware;
use App\Middleware\VerificarRepetidoMiddleware;
use App\Middleware\ValidarExistenteMiddleware;
use App\Middleware\ValidarLoginMiddleware;
use App\Middleware\ValidarAdminTokenMiddleware;
use App\Middleware\TokenValidoMiddleware;

require '../config/database.php';
require '../src/models/usuario.php';

return function ($app) {
    $app->group('/materias', function(RouteCollectorProxy $group) {
        $group->post('',MateriasController::class . ':addMateria')->add(new ValidarAdminTokenMiddleware)->add(new TokenValidoMiddleware);
        $group->get('/{id}[/]',MateriasController::class . ':mostrarMateria')->add(new TokenValidoMiddleware);
        $group->put('/{id}/{profesor}[/]',MateriasController::class . ':addProfesor')->add(new TokenValidoMiddleware);
        $group->put('/{id}[/]',UsuariosController::class . ':getAll')->add(new ValidarAdminTokenMiddleware)->add(new TokenValidoMiddleware);
        $group->get('',UsuariosController::class . ':getAll')->add(new ValidarAdminTokenMiddleware)->add(new TokenValidoMiddleware);
    });

    $app->post('/usuario[/]',UsuariosController::class . ':postOne')->add(new VerificarRepetidoMiddleware())->add(new ValidarTipoMiddleware())->add(new ValidarDatosMiddleware());

    $app->post('/login[/]',UsuariosController::class . ':login')->add(new ValidarLoginMiddleware())->add(new ValidarExistenteMiddleware());

    //$app->post('/',UsuariosController::class . ':getAll')->add(new ValidarAdminTokenMiddleware)->add(new TokenValidoMiddleware);


    
}
/*
    $app->post('/test[/]',function (Request $request, Response $response, array $args) {
        $usuario = new Usuario;
        try {
    
            $body = $request->getParsedBody(); //trae los datos que le pasas por body
    
            //$usuario->id = $body['id'];
            $usuario->correo = $body['email'];
            $usuario->clave =$body['password'];
            $usuario->perfil = $body['tipoUsr'];
    
            $msg = json_encode($usuario->save());
    
        } catch (\Throwable $th) {
            $msg = "Error: " .$th->getMessage();
        }
    
        $rta = array("success" => true,
                     "mensaje" => $msg
        );
    
        $rtaJson = json_encode($rta);
        $response->getBody()->write($rtaJson);
    
        return $response->withHeader('Content-Type', 'application/json');
    });
    */
    /*
    $app->get('/test[/]',function (Request $request, Response $response, array $args) {
        $usuario = new Usuario;
        try {
    
            $body = $request->getQueryParams(); //trae los datos que le pasas por body
    
            $id = $body['id'];
            //$usuario = Usuario::find($id); //ya viene con json encode
            //$usuario = Usuario::where('perfil','=','cliente')->get();
            $usuario = Usuario::find(5);
            //$usuario->perfil = 'veterinario';
    
            $msg = $usuario;//->save();
            //$msg = json_encode($usuario);
    
        } catch (\Throwable $th) {
            $msg = "Error: " .$th->getMessage();
        }
    
        $rta = array("success" => true,
                     "mensaje" => $msg
        );
    
        $rtaJson = json_encode($rta);
        $response->getBody()->write($rtaJson);
    
        return $response->withHeader('Content-Type', 'application/json');
    });
    */
/////////////////////////////////con capsule

    /*
    $app->post('/registro[/]',function (Request $request, Response $response, array $args) {
    
        try {
    
            //$queryString = $request->getQueryParams(); //trae los datos que le pasas en el params
            $body = $request->getParsedBody(); //trae los datos que le pasas por body
    
            $id = $body['id'];
            $email = $body['email'];
            $password =$body['password'];
            $tipoUsr = $body['tipoUsr'];
    /////////////////
            $conStr = 'mysql:host=localhost;dbname=practica_parcial';
            $user = 'root';
            $pass = '';
    
            $PDO = new PDO($conStr,$user,$pass); //instanciar pdo
    
            $query = $PDO->prepare('INSERT INTO `usuarios`(`id`, `correo`, `clave`, `perfil`) VALUES (:id,:email,:password,:tipoUsr)'); //where id = :id'); //pasarle la consulta
    
            //$query->bindParam(":id", $id, PDO::PARAM_INT); //asegura que venga del tipo especificado
            $query->bindParam(":id",$id);
            $query->bindParam(":email",$email);
            $query->bindParam(":password",$password);
            $query->bindParam(":tipoUsr",$tipoUsr);
    
            $query->execute(); //ejecutar la consulta
    
            $resultado = $PDO->lastInsertId();
            //$resultado = $query->fetchAll(PDO::FETCH_ASSOC); //traer con fetch assoc= nomnbres de columnas
    
            $msg = $resultado;
    /////////////////////////
            $usuarios = Capsule::table('usuarios')->insert([
                'id' => $id,
                'correo' => $email,
                'clave' => $password,
                'perfil' => $tipoUsr
            ]);
    
            $msg = json_encode($usuarios);
    
        } catch (\Throwable $th) {
            $msg = "Error: " .$th->getMessage();
            //echo $msg;
        }
    
        //$body = $request->getParsedBody();
    
        $rta = array("success" => true,
                     "mensaje" => $msg
        );
    
        $rtaJson = json_encode($rta);
        $response->getBody()->write($rtaJson);
    
        return $response->withHeader('Content-Type', 'application/json');
    });
        
    $app->get('/registro[/]',function (Request $request, Response $response, array $args) {
        //$usuarios = Capsule::table('usuarios')->get();
        $usuarios = Capsule::table('usuarios')->where('perfil','cliente')->select('correo','clave')->get();
        $response->getBody()->write(json_encode($usuarios));
        return $response->withHeader('Content-Type', 'application/json');
    });
    
    $app->delete('/registro[/]',function (Request $request, Response $response, array $args) {
    
        $body = $request->getQueryParams();
        
        $id = $body['id'];
        
    
        $usuarios = Capsule::table('usuarios')->where('id', $id)->delete();
        $response->getBody()->write(json_encode($usuarios));
        return $response->withHeader('Content-Type', 'application/json');
    });
}
*/
?>