<?php
use App\Models\Usuario;
test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
it('creamos contactos', function () {
    $usuario=Usuario::factory()->create();
  $this->actingAs($usuario,'sanctum');
    $this->postJson('/api/v1/contactos', [
        'nombre' => 'linda',
              
        'telefono' => '1234567890',
    ])->assertStatus(201);
});