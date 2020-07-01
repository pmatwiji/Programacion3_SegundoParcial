<?php

namespace App\Middleware;

use App\Models\Usuario;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidarLoginMiddleware {
    public function __invoke(Request $request, RequestHandler $handler): Response
        {
            
            $response = new Response();
            $usuario = new Usuario;

            $body =$request->getParsedBody();

            $mail = $usuario->where('email', $body['email'])->value('email');
            $clave = $usuario->where('email', $body['email'])->value('clave');
            
            
            if ($mail == $body['email'] && $clave == $body['clave']) {
                
                $existingContent = (string) $response->getBody();
                $response = $handler->handle($request);
                $response->getBody()->write($existingContent);
                
            } else {
                $response->getBody()->write('Contraseña incorrecta');  
            }
            
            return $response;
        }
}
?>