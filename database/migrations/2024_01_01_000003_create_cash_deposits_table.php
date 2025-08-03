<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cash_deposits', function (Blueprint $table) {
            $table->id();
            $table->string('deposit_code')->unique()->comment('Unique deposit request code');
            $table->foreignId('outlet_user_id')->constrained('users');
            $table->decimal('amount', 15, 2)->comment('Deposit amount');
            $table->text('description')->nullable()->comment('Description of the deposit');
            $table->enum('status', ['pending', 'sales_approved', 'operator_approved', 'finance_approved', 'rejected'])->default('pending')->comment('Current status of the deposit');
            $table->foreignId('sales_user_id')->nullable()->constrained('users');
            $table->timestamp('sales_approved_at')->nullable()->comment('When sales approved');
            $table->text('sales_notes')->nullable()->comment('Sales verification notes');
            $table->foreignId('operator_user_id')->nullable()->constrained('users');
            $table->timestamp('operator_approved_at')->nullable()->comment('When operator approved');
            $table->text('operator_notes')->nullable()->comment('Operator approval notes');
            $table->foreignId('depositor_user_id')->nullable()->constrained('users');
            $table->foreignId('finance_user_id')->nullable()->constrained('users');
            $table->timestamp('finance_approved_at')->nullable()->comment('When finance approved');
            $table->text('finance_notes')->nullable()->comment('Finance reconciliation notes');
            $table->timestamp('rejected_at')->nullable()->comment('When request was rejected');
            $table->text('rejection_reason')->nullable()->comment('Reason for rejection');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('deposit_code');
            $table->index('outlet_user_id');
            $table->index('status');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_deposits');
    }
};