<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetRouteTest extends TestCase
{
    public function test_llamada_con_nodos_no_existentes(){
        $notFoundNode = $this->postJson('/api/connections/getShorterRoute', ["origin"=>"Valencia","destination"=>"Palencia"]);
        $notFoundNode
            ->assertStatus(200)
            ->assertJsonFragment([
                'code' => 404,
                'message' => "No se han encontrado esos nodos"
            ]);
    }
    public function test_llamada_con_mismo_nodo_en_origen_y_destino(){
        $notFoundNode = $this->postJson('/api/connections/getShorterRoute', ["origin"=>"Barcelona","destination"=>"Barcelona"]);
        $notFoundNode
            ->assertStatus(200)
            ->assertJsonFragment([
                'code' => 403,
                'message' => "El origen y el destino coinciden"
            ]);
    }
}
