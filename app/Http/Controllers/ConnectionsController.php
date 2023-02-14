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
                    return ResponseGenerator::generateResponse("KO", 494, $e, "Error al guardar");
                }
            }
        }else{
            return ResponseGenerator::generateResponse("KO", 494, null, "Data no encontrada");
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
                $connection = Conenction::find($data->id);
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
                    return ResponseGenerator::generateResponse("KO", 494, $e, "Error al guardar");
                }
            }
        }else{
            return ResponseGenerator::generateResponse("KO", 494, null, "Data no encontrada");
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
                    return ResponseGenerator::generateResponse("KO", 494, $e, "Error al Eliminar");
                }
            }
        }else{
            return ResponseGenerator::generateResponse("KO", 494, null, "Data no encontrada");
        }

    }
    public function list(){
        try{
            $connections = Connection::all();
            return ResponseGenerator::generateResponse("OK", 200, $connections , "Rutas AcEncontradostualizado");
        }catch(\Exception $e){
            return ResponseGenerator::generateResponse("KO", 494, $e, "Error al Buscar");
        }
    }
    public function getShorterRoute(Request $request){
        $json = $request->getContent();
        $data = json_decode($json);

        if($data){
            $allNodes = Node::with(['origins','destinations'])->get();
            return ResponseGenerator::generateResponse("OK", 200, $allNodes , "Todos los nodos"); 
        
            $allRoutes = [];

            foreach($originConnections as $origin){
                $route = [$origin];
                foreach($connections as $connection){
                    if($origin->destination == $connection->origin){
                        array_push($route, $connection);
                        if($connection->destination == $data->destination){
                            array_push($allRoutes, $route);
                            $route = [];
                        }else{
                            $origin = $connection;
                        }
                    }
                }
            }

            return ResponseGenerator::generateResponse("OK", 200, $this->getFastestRoute($allRoutes) , "Ruta más rápida"); 
        }
    }
    public function getFastestRoute($allRoutes) {
        $minTime = 0;
        $fastestRoute = null;
        foreach($allRoutes as $routes){
            $totalTime = 0;
            foreach($routes as $route){
                $totalTime += $route->distance/$route->speed;
            }
            if($minTime == 0){
                $minTime = $totalTime;
                $fastestRoute = $routes;
            }else if($totalTime < $minTime){
                $minTime = $totalTime;
                $fastestRoute = $routes;
            }
        }
        return $fastestRoute;
    }
}
