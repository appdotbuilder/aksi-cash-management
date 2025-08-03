<?php

namespace Database\Factories;

use App\Models\CapitalRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CapitalRequest>
 */
class CapitalRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\CapitalRequest>
     */
    protected $model = CapitalRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'operator_approved', 'finance_approved', 'disbursed', 'rejected']);
        
        $purposes = [
            'Inventory restocking for high-demand products',
            'Equipment purchase for store operations',
            'Marketing campaign for new product launch',
            'Store renovation and improvement',
            'Additional working capital for expansion',
            'Seasonal inventory preparation',
            'Technology upgrade and POS system',
            'Staff training and development program',
        ];
        
        return [
            'request_code' => CapitalRequest::generateRequestCode(),
            'outlet_user_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 5000000, 50000000), // 5M - 50M IDR
            'purpose' => $this->faker->randomElement($purposes),
            'status' => $status,
            'operator_user_id' => $status !== 'pending' ? User::factory() : null,
            'operator_approved_at' => $status !== 'pending' ? $this->faker->dateTimeBetween('-30 days', 'now') : null,
            'operator_notes' => $status !== 'pending' ? $this->faker->optional(0.5)->sentence() : null,
            'finance_user_id' => in_array($status, ['finance_approved', 'disbursed']) ? User::factory() : null,
            'finance_approved_at' => in_array($status, ['finance_approved', 'disbursed']) ? $this->faker->dateTimeBetween('-20 days', 'now') : null,
            'finance_notes' => in_array($status, ['finance_approved', 'disbursed']) ? $this->faker->optional(0.5)->sentence() : null,
            'disbursed_at' => $status === 'disbursed' ? $this->faker->dateTimeBetween('-10 days', 'now') : null,
            'rejected_at' => $status === 'rejected' ? $this->faker->dateTimeBetween('-15 days', 'now') : null,
            'rejection_reason' => $status === 'rejected' ? $this->faker->sentence() : null,
        ];
    }

    /**
     * Indicate that the request is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'operator_user_id' => null,
            'operator_approved_at' => null,
            'operator_notes' => null,
            'finance_user_id' => null,
            'finance_approved_at' => null,
            'finance_notes' => null,
            'disbursed_at' => null,
            'rejected_at' => null,
            'rejection_reason' => null,
        ]);
    }

    /**
     * Indicate that the request is disbursed.
     */
    public function disbursed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'disbursed',
            'operator_user_id' => User::factory(),
            'operator_approved_at' => $this->faker->dateTimeBetween('-30 days', '-20 days'),
            'operator_notes' => $this->faker->optional(0.5)->sentence(),
            'finance_user_id' => User::factory(),
            'finance_approved_at' => $this->faker->dateTimeBetween('-20 days', '-10 days'),
            'finance_notes' => $this->faker->optional(0.5)->sentence(),
            'disbursed_at' => $this->faker->dateTimeBetween('-10 days', 'now'),
            'rejected_at' => null,
            'rejection_reason' => null,
        ]);
    }
}