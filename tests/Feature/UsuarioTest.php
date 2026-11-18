<?php
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Attributes\Refreshes;
use Illuminate\Foundation\Testing\RefreshDatabase;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});



uses(RefreshDatabase::class);
test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

function loginData(array $cambios = []): array
{
    return array_merge([
        'email' => 'ana@taskflow.test',
        'password' => 'secreta123',
        'device_name' => 'pruebas',
    ], $cambios);
}

beforeEach(function () {
    $this->usuario = Usuario::factory()->create([
        'nombre'=>"linda",
        "apellido"=>"atencio",
        'email' => 'ana@taskflow.test',
      
        'password' => 'secreta123',
    ]);
});


it('entrega un token con credenciales correctas', function () {
    $this->postJson('/api/v1/login/usuario', loginData())
        ->assertOk()
        ->assertJsonStructure(['token', 'token_type', 'usuario' => ['id', 'nombre','apellido' ,'email','telefono','foto']])
        ->assertJsonPath('usuario.email', 'ana@taskflow.test')
        ->assertJsonMissingPath('user.password');

    expect($this->user->tokens()->count())->toBe(1);
});
