<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers\ResponseGenerator;
use Illuminate\Support\Facades\Validator;
use App\Models\Node;

class NodesController extends Controller
{
    public function create(Request $request){
        $json = $request->getContent();
        $data = json_decode($json);

        if($data){
            $validate = Validator::make(json_decode($json,true), [
                'name' => 'required|string|unique:nodes,name'
            ]);
            if($validate->fails()){
                return ResponseGenerator::generateResponse("KO", 422, null, $validate->errors());
            }else {
                $node = new Node();
                $node->name = $data->name;
                try{
                    $node->save();
                    return ResponseGenerator::generateResponse("OK", 200, $node, "Nodo Guardado");
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
                'id' => 'required|numeric|exists:nodes,id'
            ]);
            if($validate->fails()){
                return ResponseGenerator::generateResponse("KO", 422, null, $validate->errors());
            }else {
                $node = Node::find($data->id);
                try{
                    $node->delete();
                    return ResponseGenerator::generateResponse("OK", 200, null, "Nodo Borrado");
                }catch(\Exception $e){
                    return ResponseGenerator::generateResponse("KO", 494, $e, "Error al borrar");
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
                'id' => 'required|numeric|exists:nodes,id',
                'name' => 'required|string|unique:nodes,name'
            ]);
            if($validate->fails()){
                return ResponseGenerator::generateResponse("KO", 422, null, $validate->errors());
            }else {
                $node = Node::find($data->id);
                $node->name = $data->name;
                try{
                    $node->save();
                    return ResponseGenerator::generateResponse("OK", 200, $node, "Nodo Actualizado");
                }catch(\Exception $e){
                    return ResponseGenerator::generateResponse("KO", 494, $e, "Error al Actualizar");
                }
            }

        }else{
            return ResponseGenerator::generateResponse("KO", 494, null, "Data no encontrada");
        }
    }
    public function list(){
    
        try{
            $nodes = Node::all();
            return ResponseGenerator::generateResponse("OK", 200, $nodes , "Nodos AcEncontradostualizado");
        }catch(\Exception $e){
            return ResponseGenerator::generateResponse("KO", 494, $e, "Error al Buscar");
        }
        
    }
}
