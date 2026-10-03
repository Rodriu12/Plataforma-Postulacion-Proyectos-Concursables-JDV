<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacion_id')->constrained('organizaciones')->cascadeOnDelete();

            $table->foreignId('proyecto_id')->nullable()->constrained('proyectos')->nullOnDelete();

            $table->string('nombre');
            $table->string('categoria')->default('otro');
            $table->text('descripcion')->nullable();
            $table->string('unidad')->default('unidad(es)');

            $table->unsignedInteger('cantidad_total')->default(0);

            $table->string('ubicacion')->nullable();
            $table->enum('estado', ['disponible', 'agotado', 'de_baja'])->default('disponible');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario_items');
    }
};
