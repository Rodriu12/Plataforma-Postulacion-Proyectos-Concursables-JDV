<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('rut')->unique()->nullable()->after('email');
            $table->string('phone')->nullable()->after('rut');
            $table->string('role')->default('vecino')->after('phone');
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rut', 'phone', 'role', 'is_active']);
        });
    }
};