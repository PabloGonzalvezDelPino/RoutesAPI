<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers\ResponseGenerator;
use Illuminate\Support\Facades\Validator;
use App\Models\Connection;
use App\Models\Node;

class ConnectionsController extends Controller
{
    public function create(Request $request){
        $json = $request->getContent();
        $data = json_decode($json);

        if($data){
            $validate = Validator::make(json_decode($json,true), [
                'name' => 'required|string|unique:connections,name',
                'origin' => 'required|numeric|exists:nodes,id',
                'destination' => 'required|numeric|exists:nodes,id',
                'distance' => 'required|numeric',
                'speed' => 'required|numeric',
                'unidirectional' => 'required|numeric'
            ]);
            if($validate->fails()){
                return ResponseGenerator::generateResponse("KO", 422, null, $validate->errors());
            }else {
                $connection = new Connection();
                $connection->name = $data->name;
                $connection->origin = $data->origin;
                $connection->destination = $data->destination;
                $connection->distance = $data->distance;
                $connection->speed = $data->speed;
                $connection->unidirectional = $data->unidirectional;
                try{
                    $connection->save();
                    return ResponseGenerator::generateResponse("OK", 200, $connection, "Ruta Guardada");
                }catch(\Exception $e){
                    return ResponseGenerator::generateResponse("KO", 400, $e, "Error al guardar");
                }
            }
        }else{
            return ResponseGenerator::generateResponse("KO", 404, null, "Data no encontrada");
        }
    }
    public function edit(Request $request){
        $json = $request->getContent();
        $data = json_decode($json);
        if($data){
            $validate = Validator::make(json_decode($json,true), [
                'id' => 'required|numeric|exists:connections,id',
                'name' => 'required|string|unique:connections,name',
                'origin' => 'required|numeric|exists:nodes,id',
                'destination' => 'required|numeric|exists:nodes,id',
                'distance' => 'required|numeric',
                'speed' => 'required|numeric',
                'unidirectional' => 'required|numeric'
            ]);
            if($validate->fails()){
                return ResponseGenerator::generateResponse("KO", 422, null, $validate->errors());
            }else {
                $connection = Connection::find($data->id);
                $connection->name = $data->name;
                $connection->origin = $data->origin;
                $connection->destination = $data->destination;
                $connection->distance = $data->distance;
                $connection->speed = $data->speed;
                $connection->unidirectional = $data->unidirectional;
                try{
                    $connection->save();
                    return ResponseGenerator::generateResponse("OK", 200, $connection, "Ruta Guardada");
                }catch(\Exception $e){
                    return ResponseGenerator::generateResponse("KO", 400, $e, "Error al guardar");
                }
            }
        }else{
            return ResponseGenerator::generateResponse("KO", 404, null, "Data no encontrada");
        }
    }
    public function delete(Request $request){
        $json = $request->getContent();
        $data = json_decode($json);

        if($data){
            $validate = Validator::make(json_decode($json,true), [
                'id' => 'required|numeric|exists:connections,id'
            ]);
            if($validate->fails()){
                return ResponseGenerator::generateResponse("KO", 422, null, $validate->errors());
            }else {
                $connection = Connection::find($data->id);
                try{
                    $connection->delete();
                    return ResponseGenerator::generateResponse("OK", 200, $connection, "Ruta Eliminada");
                }catch(\Exception $e){
                    return ResponseGenerator::generateResponse("KO", 400, $e, "Error al Eliminar");
                }
            }
        }else{
            return ResponseGenerator::generateResponse("KO", 404, null, "Data no encontrada");
        }
    }
    public function list(){
        try{
            $connections = Connection::all();
            return ResponseGenerator::generateResponse("OK", 200, $connections , "Rutas Encontradas Correctamente");
        }catch(\Exception $e){
            return ResponseGenerator::generateResponse("KO", 404, $e, "Data No Encontrada");
        }
    }
    public function getShorterRoute(Request $request){
        $json = $request->getContent();
        $data = json_decode($json);

        if($data){
            //Compruebo que el destino y el origen sean diferentes
            if($data->origin == $data->destination){
                return ResponseGenerator::generateResponse("KO", 403, null, "El origen y el destino coinciden"); 
            }
            $allConections = [];
            //Obtengo todas las conexiones y junto los arrays de conexiones
            $allNodes = Node::with(['origins','destinations'])->get();
            foreach(json_decode($allNodes) as $node){
                $node->origins = array_merge($node->origins, $node->destinations);
                $node->destinations = [];
                array_push($allConections, $node); 
            }
            $originNode = 0;
            $destinationNode = 0;
            //Encuentro las ids de origen y destino
            foreach($allConections as $con){
                if($con->name == $data->origin){
                    $originNode = $con;
                }
                if($con->name == $data->destination){
                    $destinationNode = $con;
                } 
            }
            if(empty($originNode) || empty($destinationNode) ){
                return ResponseGenerator::generateResponse("KO", 404, null, "No se han encontrado esos nodos");
            }
            
            $times = []; 
            $path = [];
            $visited = [];
            $allRoutes = [];
            $routeTime = 0;
            $this->searchRoutes($allConections,$originNode,$destinationNode,$path,$visited,$times,$routeTime,$allRoutes,$data->direction);
            if(!empty($times)){
                $min = min($times);
                $fastestRoute = $allRoutes[array_search($min, $times)]; 
                return ResponseGenerator::generateResponse("OK", 200, [$this->showTheFastestRoute($fastestRoute),$fastestRoute] , "Ruta encontrada");
            }else{
                return ResponseGenerator::generateResponse("KO", 404, null , "No se han encontrado rutas");
            }
        }
    }
    public function showTheFastestRoute($fastestRoute){
        $resultado = [];
        $resultado[] = "El camino más corto es: ";
        foreach($fastestRoute as $node){
            $resultado[] =$node->name . " (id: $node->id)";
        }
        return $resultado;
    }
    public function searchRoutes($nodes,$start,$end,$path,&$visited, &$times,&$routeTime,&$allRoutes,$direction){
        $caminoEncontrado = false;
        $path[] = $start;
        if($start == $end){
            $caminoEncontrado = $path;
            $times[] = $routeTime;
            $routeTime = 0;
            $allRoutes[] = $caminoEncontrado;
        }else {
            foreach($nodes[$start->id -1]->origins as $origen){
                if($origen->origin == $start->id  && !in_array($origen,$visited) && $origen->unidirectional == $direction){
                    $visited[] = $origen;
                    $routeTime += $origen->distance/$origen->speed;
                    $respuesta = $this->searchRoutes($nodes,$nodes[$origen->destination-1],$end,$path,$visited, $times,$routeTime,$allRoutes,$direction);
                    if($respuesta && !$caminoEncontrado){
                        
                        $caminoEncontrado = $respuesta;
                    }
                }
                if($origen->destination == $start->id && !in_array($origen, $visited) && $origen->unidirectional == $direction){
                    $visited[] = $origen;
                    $routeTime += $origen->distance/$origen->speed;
                    $respuesta = $this->searchRoutes($nodes,$nodes[$origen->origin-1],$end,$path,$visited, $times,$routeTime,$allRoutes,$direction);
                    if($respuesta && !$caminoEncontrado){
                        
                        $caminoEncontrado = $respuesta;
                    }
                }
            }
        }
        
        return $allRoutes;
    }
    
}
