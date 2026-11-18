<?php

namespace Database\Factories;

use App\Models\Contacto;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Usuario;
/**
 * @extends Factory<Contacto>
 */
class ContactoFactory extends Factory
{
   public function definition(): array
    {
        return [
            'usuario_id' => Usuario::query()->inRandomOrder()->value('id'),
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'foto' => fake()->imageUrl($category = 'people'),
            'email' => fake()->unique()->safeEmail(),
            'telefono' => fake()->phoneNumber(),
        ];
    }
}
