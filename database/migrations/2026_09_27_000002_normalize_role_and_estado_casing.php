<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE users SET role = LOWER(TRIM(role)) WHERE role IS NOT NULL");
        DB::statement("UPDATE proyectos SET estado = LOWER(TRIM(estado)) WHERE estado IS NOT NULL");
    }

    public function down(): void
    {
        
    }
};
