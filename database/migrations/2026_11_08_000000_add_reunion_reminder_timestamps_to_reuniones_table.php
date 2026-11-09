<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reuniones', function (Blueprint $table) {
            $table->timestamp('recordatorio_dia_enviado_at')->nullable()->after('estado');
            $table->timestamp('recordatorio_hora_enviado_at')->nullable()->after('recordatorio_dia_enviado_at');
        });
    }

    public function down(): void
    {
        Schema::table('reuniones', function (Blueprint $table) {
            $table->dropColumn(['recordatorio_dia_enviado_at', 'recordatorio_hora_enviado_at']);
        });
    }
};
