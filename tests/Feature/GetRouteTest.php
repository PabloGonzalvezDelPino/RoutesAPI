<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetRouteTest extends TestCase
{
    public function test_llamada_con_nodos_no_existentes(){
        $notFoundNode = $this->postJson('/api/connections/getShorterRoute', ["origin"=>"Valencia","destination"=>"Palencia","direction"=>1]);
        $notFoundNode
            ->assertStatus(200)
            ->assertJson([
                'code' => 404,
                'message' => "No se han encontrado esos nodos"
            ]);
    }
    public function test_llamada_con_mismo_nodo_en_origen_y_destino(){
        $notFoundNode = $this->postJson('/api/connections/getShorterRoute', ["origin"=>"Barcelona","destination"=>"Barcelona","direction"=>1]);
        $notFoundNode
            ->assertStatus(200)
            ->assertJson([
                'code' => 403,
                'message' => "El origen y el destino coinciden"
            ]);
    }
    public function test_ruta_inexistente_o_inalcanzable(){
        $notFoundNode = $this->postJson('/api/connections/getShorterRoute', ["origin"=>"Lugo","destination"=>"Castilla","direction"=>1]);
        $notFoundNode
            ->assertStatus(200)
            ->assertJson([
                'code' => 404,
                'message' => "No se han encontrado rutas"
            ]);
    }
    public function test_ruta_correcta(){
        $notFoundNode = $this->postJson('/api/connections/getShorterRoute', ["origin"=>"Talavera","destination"=>"Barcelona","direction"=>1]);
        $notFoundNode
            ->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'message' => "Ruta encontrada"
            ]);
    }
}
