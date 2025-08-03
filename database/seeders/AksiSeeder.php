<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CashDeposit;
use App\Models\CapitalRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'AKSI Administrator',
            'email' => 'admin@aksi.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'outlet_code' => null,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create finance users
        $finance1 = User::create([
            'name' => 'Finance Manager',
            'email' => 'finance@aksi.com',
            'password' => Hash::make('password'),
            'role' => 'finance',
            'outlet_code' => null,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $finance2 = User::factory()->finance()->create();

        // Create operators
        $operator1 = User::create([
            'name' => 'Operations Manager',
            'email' => 'operator@aksi.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'outlet_code' => null,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $operator2 = User::factory()->operator()->create();

        // Create sales representatives
        $sales1 = User::create([
            'name' => 'Sales Representative',
            'email' => 'sales@aksi.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
            'outlet_code' => null,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $sales2 = User::factory()->sales()->create();
        $sales3 = User::factory()->sales()->create();

        // Create depositors
        $depositor1 = User::create([
            'name' => 'Cash Depositor',
            'email' => 'depositor@aksi.com',
            'password' => Hash::make('password'),
            'role' => 'penyetor',
            'outlet_code' => null,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $depositor2 = User::factory()->penyetor()->create();
        $depositor3 = User::factory()->penyetor()->create();

        // Create outlet users
        $outlet1 = User::create([
            'name' => 'Main Outlet Jakarta',
            'email' => 'outlet1@aksi.com',
            'password' => Hash::make('password'),
            'role' => 'outlet',
            'outlet_code' => 'OUT-001',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $outlet2 = User::create([
            'name' => 'Outlet Surabaya',
            'email' => 'outlet2@aksi.com',
            'password' => Hash::make('password'),
            'role' => 'outlet',
            'outlet_code' => 'OUT-002',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create more outlet users
        $outlets = User::factory()->count(8)->outlet()->create();

        // Get all users for relationships
        $allOutlets = User::where('role', 'outlet')->get();
        $allSales = User::where('role', 'sales')->get();
        $allOperators = User::where('role', 'operator')->get();
        $allDepositors = User::where('role', 'penyetor')->get();
        $allFinance = User::where('role', 'finance')->get();

        // Create cash deposits with proper relationships
        foreach ($allOutlets as $outlet) {
            // Create some pending deposits
            CashDeposit::factory()->count(2)->pending()->create([
                'outlet_user_id' => $outlet->id,
            ]);

            // Create some approved deposits with full workflow
            CashDeposit::factory()->count(3)->create([
                'outlet_user_id' => $outlet->id,
                'sales_user_id' => $allSales->random()->id,
                'operator_user_id' => $allOperators->random()->id,
                'depositor_user_id' => $allDepositors->random()->id,
                'finance_user_id' => $allFinance->random()->id,
                'status' => 'finance_approved',
                'sales_approved_at' => now()->subDays(random_int(5, 15)),
                'operator_approved_at' => now()->subDays(random_int(3, 10)),
                'finance_approved_at' => now()->subDays(random_int(1, 5)),
            ]);

            // Create some in-progress deposits
            CashDeposit::factory()->count(1)->create([
                'outlet_user_id' => $outlet->id,
                'sales_user_id' => $allSales->random()->id,
                'status' => 'sales_approved',
                'sales_approved_at' => now()->subDays(random_int(1, 3)),
            ]);

            CashDeposit::factory()->count(1)->create([
                'outlet_user_id' => $outlet->id,
                'sales_user_id' => $allSales->random()->id,
                'operator_user_id' => $allOperators->random()->id,
                'depositor_user_id' => $allDepositors->random()->id,
                'status' => 'operator_approved',
                'sales_approved_at' => now()->subDays(random_int(3, 8)),
                'operator_approved_at' => now()->subDays(random_int(1, 5)),
            ]);
        }

        // Create capital requests with proper relationships
        foreach ($allOutlets as $outlet) {
            // Create some pending capital requests
            CapitalRequest::factory()->count(1)->pending()->create([
                'outlet_user_id' => $outlet->id,
            ]);

            // Create some approved capital requests
            CapitalRequest::factory()->count(2)->create([
                'outlet_user_id' => $outlet->id,
                'operator_user_id' => $allOperators->random()->id,
                'finance_user_id' => $allFinance->random()->id,
                'status' => 'disbursed',
                'operator_approved_at' => now()->subDays(random_int(10, 20)),
                'finance_approved_at' => now()->subDays(random_int(5, 15)),
                'disbursed_at' => now()->subDays(random_int(1, 10)),
            ]);

            // Create some in-progress capital requests
            CapitalRequest::factory()->count(1)->create([
                'outlet_user_id' => $outlet->id,
                'operator_user_id' => $allOperators->random()->id,
                'status' => 'operator_approved',
                'operator_approved_at' => now()->subDays(random_int(1, 5)),
            ]);
        }

        $this->command->info('AKSI seeder completed successfully!');
        $this->command->info('Demo accounts created:');
        $this->command->info('- Admin: admin@aksi.com / password');
        $this->command->info('- Finance: finance@aksi.com / password');
        $this->command->info('- Operator: operator@aksi.com / password');
        $this->command->info('- Sales: sales@aksi.com / password');
        $this->command->info('- Depositor: depositor@aksi.com / password');
        $this->command->info('- Outlet 1: outlet1@aksi.com / password');
        $this->command->info('- Outlet 2: outlet2@aksi.com / password');
    }
}