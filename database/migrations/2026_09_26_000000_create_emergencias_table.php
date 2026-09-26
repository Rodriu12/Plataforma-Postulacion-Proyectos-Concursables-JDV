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
        Schema::create('emergencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vecino_id')->nullable()->constrained('vecinos')->nullOnDelete();
            $table->foreignId('organizacion_id')->nullable()->constrained('organizaciones')->nullOnDelete();
            $table->string('tipo');
            $table->text('descripcion');
            $table->string('ubicacion');
            $table->string('evidencia')->nullable();
            $table->enum('estado', ['pendiente', 'en_atencion', 'resuelta'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergencias');
    }
};
