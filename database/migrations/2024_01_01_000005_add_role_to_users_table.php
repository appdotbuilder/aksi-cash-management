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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['outlet', 'sales', 'operator', 'penyetor', 'finance', 'admin'])->default('outlet')->after('email')->comment('User role in the system');
            $table->string('outlet_code')->nullable()->after('role')->comment('Outlet code for outlet users');
            $table->boolean('is_active')->default(true)->after('outlet_code')->comment('Whether user is active');
            
            // Index for performance
            $table->index('role');
            $table->index('outlet_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['outlet_code']);
            $table->dropColumn(['role', 'outlet_code', 'is_active']);
        });
    }
};