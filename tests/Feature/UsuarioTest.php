
<?php
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Attributes\Refreshes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

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
        'device' => 'pruebas',
    ], $cambios);
}

beforeEach(function () {
    $this->usuario = Usuario::factory()->create([
        'nombre'=>"linda",
        "apellido"=>"atencio",
        'email' => 'ana@taskflow.test',
      
        'password' =>Hash::make('secreta123') ,
    ]);
});


it('entrega un token con credenciales correctas', function () {

    $this->postJson('/api/v1/login/usuario', loginData())

        ->assertOk()

        ->assertJsonStructure([
            'token',
            'TokenType',
            'usuario_resource' => [
                'id',
                'nombre',
                'apellido',
                'email',
               
            ]
        ])

        ->assertJsonPath('TokenType', 'Bearer')

        ->assertJsonPath('usuario_resource.email', 'ana@taskflow.test')

        ->assertJsonMissingPath('usuario_resource.password');

    expect($this->usuario->tokens()->count())->toBe(1);
});

it('cierra la sesion del dispositivo actual', function () {

    $token = $this->usuario->createToken('pruebas');

    $this->withToken($token->plainTextToken)
        ->postJson('/api/v1/logout')
        ->assertOk();

    expect($this->usuario->tokens()->count())->toBe(0);
});
it('obtener usuario', function () {

    Sanctum::actingAs($this->usuario);

    $this->getJson('/api/v1/me')
        ->assertOk()
        ->assertJsonPath('data.email', 'ana@taskflow.test');

});

//dump() para ver que tipo de dato manda la respuesta