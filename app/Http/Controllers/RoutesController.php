<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers\ResponseGenerator;
use Illuminate\Support\Facades\Validator;
use App\Models\Route;
use App\Models\Node;

class RoutesController extends Controller
{
    public function create(Request $request){
        $json = $request->getContent();
        $data = json_decode($json);

        if($data){
            $validate = Validator::make(json_decode($json,true), [
                'name' => 'required|string|unique:routes,name',
                'startId' => 'required|numeric|exists:nodes,id',
                'destinationId' => 'required|numeric|exists:nodes,id',
                'distance' => 'required|numeric',
                'speed' => 'required|numeric',
                'type' => 'required|in:unidireccional,bidireccional'
            ]);
            if($validate->fails()){
                return ResponseGenerator::generateResponse("KO", 422, null, $validate->errors());
            }else {
                //$startNode = Node::find($data->startId);
                //$destinationNode = Node::find($data->destinationId);
                $route = new Route();
                $route->name = $data->name;
                $route->start_id = $data->startId;
                $route->destination_id = $data->destinationId;
                $route->distance = $data->distance;
                $route->speed = $data->speed;
                try{
                    $route->save();
                    return ResponseGenerator::generateResponse("OK", 200, $route, "Ruta Guardada");
                }catch(\Exception $e){
                    return ResponseGenerator::generateResponse("KO", 494, $e, "Error al guardar");
                }
            }
        }else{
            return ResponseGenerator::generateResponse("KO", 494, null, "Data no encontrada");
        }

    }
}
