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
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacion_id')
                ->constrained('organizaciones')
                ->cascadeOnDelete();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('fuente_financiamiento')->nullable();
            $table->integer('monto_solicitado')->default(0);
            $table->integer('monto_adjudicado')->nullable();
            $table->enum('estado', [
                'borrador', 
                'en_postulacion', 
                'adjudicado', 
                'rechazado', 
                'en_ejecucion', 
                'rendido'
            ])->default('borrador');
            $table->date('fecha_postulacion')->nullable();
            $table->date('fecha_adjudicacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
