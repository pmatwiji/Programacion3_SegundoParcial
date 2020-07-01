<?php

namespace App\Middleware;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidarTipoMiddleware {
    public function __invoke(Request $request, RequestHandler $handler): Response
        {
            
            $response = new Response();

            $body =$request->getParsedBody();
            
            if (intval($body['tipo']) == 1 || intval($body['tipo']) == 2 || intval($body['tipo']) == 3) {
                
                $existingContent = (string) $response->getBody();
                $response = $handler->handle($request);
                $response->getBody()->write($existingContent);
                
            } else {
                $response->getBody()->write('Tipo de usuario incorrecto');  
            }
            
            return $response;
        }
}
?>