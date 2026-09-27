<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emergencias', function (Blueprint $table) {
            $table->foreignId('voluntario_id')->nullable()->after('organizacion_id')->constrained('voluntarios')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('emergencias', function (Blueprint $table) {
            $table->dropConstrainedForeignId('voluntario_id');
        });
    }
};
