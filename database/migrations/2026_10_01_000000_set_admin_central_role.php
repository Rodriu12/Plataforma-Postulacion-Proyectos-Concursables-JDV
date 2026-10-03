<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('email', 'adminvecindar@gmail.com')
            ->update(['role' => 'admin_central', 'organizacion_id' => null]);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('email', 'adminvecindar@gmail.com')
            ->update(['role' => 'presidente']);
    }
};
