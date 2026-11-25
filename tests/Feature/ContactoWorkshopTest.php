<?php

use App\Models\Contacto;
use App\Models\Usuario;
use Laravel\Sanctum\Sanctum;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
it('creamos un contacto', function () {
    $usuario=Usuario::factory()->create();
    $this->actingAs($usuario,'sanctum');
    $this->postJson('/api/v1/contactos', [
        'nombre' => 'linda',
        
        'telefono' => '1234567890',
    ])->assertStatus(201);
});

it('obtener contactos sin autorizar el usuario', function () {
 
    $this->getJson('/api/v1/contactos')->assertStatus(401);
});

it('creamos contactos SIN TELEFONO', function () {
    $usuario=Usuario::factory()->create();
    $this->actingAs($usuario,'sanctum');
    $this->postJson('/api/v1/contactos', [
        'nombre' => 'linda',
        
       
    ])->assertStatus(422);
});

test('devuelve 404 para un contacto ajeno', function () {
    $owner = Usuario::factory()->create();
    $visitor = Usuario::factory()->create();
    $contact = Contacto::factory()->for($owner)->create([
        'nombre' => 'Contacto privado',
    ]);
    Sanctum::actingAs($visitor);
        $this->getJson('/api/v1/contacts/'.$contact->id)
        ->assertNotFound()
        ->assertJsonMissing(['nombre' => 'Contacto privado']);

    $this->assertDatabaseHas('contactos', [
        'id' => $contact->id,
        'usuario_id' => $owner->id,
        'nombre' => 'Contacto privado',
    ]);
});

it('creamos contacto con numero repetido', function () {
    $usuario=Usuario::factory()->create();
    $this->actingAs($usuario,'sanctum');
    $this->postJson('/api/v1/contactos', [
        'nombre' => 'linda',
        
        'telefono' => '1234567890',
    ])->assertStatus(422);
});