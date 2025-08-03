<?php

namespace Database\Factories;

use App\Models\CashDeposit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CashDeposit>
 */
class CashDepositFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\CashDeposit>
     */
    protected $model = CashDeposit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'sales_approved', 'operator_approved', 'finance_approved', 'rejected']);
        
        return [
            'deposit_code' => CashDeposit::generateDepositCode(),
            'outlet_user_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 100000, 10000000), // 100k - 10M IDR
            'description' => $this->faker->optional(0.7)->sentence(),
            'status' => $status,
            'sales_user_id' => $status !== 'pending' ? User::factory() : null,
            'sales_approved_at' => $status !== 'pending' ? $this->faker->dateTimeBetween('-30 days', 'now') : null,
            'sales_notes' => $status !== 'pending' ? $this->faker->optional(0.5)->sentence() : null,
            'operator_user_id' => in_array($status, ['operator_approved', 'finance_approved']) ? User::factory() : null,
            'operator_approved_at' => in_array($status, ['operator_approved', 'finance_approved']) ? $this->faker->dateTimeBetween('-20 days', 'now') : null,
            'operator_notes' => in_array($status, ['operator_approved', 'finance_approved']) ? $this->faker->optional(0.5)->sentence() : null,
            'depositor_user_id' => in_array($status, ['operator_approved', 'finance_approved']) ? User::factory() : null,
            'finance_user_id' => $status === 'finance_approved' ? User::factory() : null,
            'finance_approved_at' => $status === 'finance_approved' ? $this->faker->dateTimeBetween('-10 days', 'now') : null,
            'finance_notes' => $status === 'finance_approved' ? $this->faker->optional(0.5)->sentence() : null,
            'rejected_at' => $status === 'rejected' ? $this->faker->dateTimeBetween('-15 days', 'now') : null,
            'rejection_reason' => $status === 'rejected' ? $this->faker->sentence() : null,
        ];
    }

    /**
     * Indicate that the deposit is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'sales_user_id' => null,
            'sales_approved_at' => null,
            'sales_notes' => null,
            'operator_user_id' => null,
            'operator_approved_at' => null,
            'operator_notes' => null,
            'depositor_user_id' => null,
            'finance_user_id' => null,
            'finance_approved_at' => null,
            'finance_notes' => null,
            'rejected_at' => null,
            'rejection_reason' => null,
        ]);
    }

    /**
     * Indicate that the deposit is approved by finance.
     */
    public function financeApproved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'finance_approved',
            'sales_user_id' => User::factory(),
            'sales_approved_at' => $this->faker->dateTimeBetween('-30 days', '-20 days'),
            'sales_notes' => $this->faker->optional(0.5)->sentence(),
            'operator_user_id' => User::factory(),
            'operator_approved_at' => $this->faker->dateTimeBetween('-20 days', '-10 days'),
            'operator_notes' => $this->faker->optional(0.5)->sentence(),
            'depositor_user_id' => User::factory(),
            'finance_user_id' => User::factory(),
            'finance_approved_at' => $this->faker->dateTimeBetween('-10 days', 'now'),
            'finance_notes' => $this->faker->optional(0.5)->sentence(),
            'rejected_at' => null,
            'rejection_reason' => null,
        ]);
    }
}