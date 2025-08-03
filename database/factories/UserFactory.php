<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role = $this->faker->randomElement(['outlet', 'sales', 'operator', 'penyetor', 'finance', 'admin']);
        
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => $role,
            'outlet_code' => $role === 'outlet' ? 'OUT-' . str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT) : null,
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is an outlet.
     */
    public function outlet(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'outlet',
            'outlet_code' => 'OUT-' . str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT),
        ]);
    }

    /**
     * Indicate that the user is a sales representative.
     */
    public function sales(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'sales',
            'outlet_code' => null,
        ]);
    }

    /**
     * Indicate that the user is an operator.
     */
    public function operator(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'operator',
            'outlet_code' => null,
        ]);
    }

    /**
     * Indicate that the user is a depositor.
     */
    public function penyetor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'penyetor',
            'outlet_code' => null,
        ]);
    }

    /**
     * Indicate that the user is a finance user.
     */
    public function finance(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'finance',
            'outlet_code' => null,
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'outlet_code' => null,
        ]);
    }
}