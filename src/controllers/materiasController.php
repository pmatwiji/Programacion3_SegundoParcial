<?php
//aca van todas las consultas de queries
namespace App\Controllers;

use App\Models\Materia;
use App\Models\Inscripto;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;



class MateriasController{


    public function addMateria(Request $request, Response $response) {
        
        $materia = new Materia();
        $success = false;
        try {
    
            $body = $request->getParsedBody(); 
    
            $materia->materia = $body['materia'];
            $materia->cuatrimestre = intval($body['cuatrimestre']);
            $materia->vacantes = intval($body['vacantes']);
            $materia->profesor_id = intval($body['profesor']);

            $msg = json_encode($materia->save());
            $success = true;
          

        } catch (\Throwable $th) {
            $msg = "Error: " .$th->getMessage();
        }
    
        $rta = array("success" => $success,
                     "mensaje" => $msg
        );
    
        $rtaJson = json_encode($rta);
        $response->getBody()->write($rtaJson);
    
        return $response;
    }

    public function mostrarMateria(Request $request, Response $response,array $args) {
        
        $materia = new Materia();
        $inscripto = new Inscripto();
        $id_materia = $args['id'];
        $headers = getallheaders();
        $token = $headers['token'] ?? '';
        $key = 'key';
        try {
            $decoded = JWT::decode($token, $key, array('HS256'));
        } catch (\Throwable $th) {
            $msg = "Error: " .$th->getMessage();
        }

        if($decoded->tipo_id == 1) {
            $materia = $materia->find($id_materia)->join('users','profesor_id','=','users.id')->select('materia','cuatrimestre','vacantes','users.nombre')->get();$success = true;
    
            $rta = array("success" => $success,
                     "materia" => $materia
            );

        } else {
            $materia = $materia->find($id_materia)->join('users','profesor_id','=','users.id')->select('materia','cuatrimestre','vacantes','users.nombre')->get();
            $inscripto = $inscripto->where('materia_id','=',$id_materia)->join('users','alumno_id','=','users.id')->select('users.email','users.nombre','users.legajo')->get();

            $success = true;
    
            $rta = array("success" => $success,
                     "materia" => $materia,
                     "inscriptos" => $inscripto
            );
        }


        $rtaJson = json_encode($rta);
        $response->getBody()->write($rtaJson);
    
        return $response;
    }

    public function addProfesor(Request $request, Response $response , array $args){


        $materias = new Materia();
        $materia = $materias->find($args["id"]);
        $success = false;

        if ($materia!=null) {
            $materia->profesor_id =$args["profesor"];
            $success = true;
            $msg = json_encode($materia->save());
        }else {
            $msg = "Materia inexistente";
        }

        $rta = (array("success"=>$success,
                      "Mensaje"=>$msg)
                );


        $rtaJson = json_encode($rta);
        $response->getBody()->write($rtaJson);

         return $response;

    }

    public function asignarAlumno(Request $request, Response $response,array $args) {
        
        $materia = new Materia();
        $id_materia = $args['id'];
        $headers = getallheaders();
        $token = $headers['token'] ?? '';
        $key = 'key';
        try {
            $decoded = JWT::decode($token, $key, array('HS256'));
        } catch (\Throwable $th) {
            $msg = "Error: " .$th->getMessage();
        }

        if($decoded->tipo_id == 1) {


        } else {
            
            $success = false;
            $msg = "No es alumno";
            
        }

        $rta = array("success" => $success,
                        "mensaje" => $msg
        );
        
        $rtaJson = json_encode($rta);
        $response->getBody()->write($rtaJson);
    
        return $response;
    }




}

?>