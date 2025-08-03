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
        Schema::create('capital_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_code')->unique()->comment('Unique capital request code');
            $table->foreignId('outlet_user_id')->constrained('users');
            $table->decimal('amount', 15, 2)->comment('Requested capital amount');
            $table->text('purpose')->comment('Purpose of the capital request');
            $table->enum('status', ['pending', 'operator_approved', 'finance_approved', 'disbursed', 'rejected'])->default('pending')->comment('Current status of the request');
            $table->foreignId('operator_user_id')->nullable()->constrained('users');
            $table->timestamp('operator_approved_at')->nullable()->comment('When operator approved');
            $table->text('operator_notes')->nullable()->comment('Operator approval notes');
            $table->foreignId('finance_user_id')->nullable()->constrained('users');
            $table->timestamp('finance_approved_at')->nullable()->comment('When finance approved');
            $table->text('finance_notes')->nullable()->comment('Finance approval notes');
            $table->timestamp('disbursed_at')->nullable()->comment('When funds were disbursed');
            $table->timestamp('rejected_at')->nullable()->comment('When request was rejected');
            $table->text('rejection_reason')->nullable()->comment('Reason for rejection');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('request_code');
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
        Schema::dropIfExists('capital_requests');
    }
};