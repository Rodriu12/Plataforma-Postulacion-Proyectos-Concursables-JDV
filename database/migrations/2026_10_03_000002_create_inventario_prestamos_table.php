<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario_prestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventario_item_id')->constrained('inventario_items')->cascadeOnDelete();

            $table->foreignId('vecino_id')->nullable()->constrained('vecinos')->nullOnDelete();
            $table->foreignId('voluntario_id')->nullable()->constrained('voluntarios')->nullOnDelete();

            $table->unsignedInteger('cantidad')->default(1);
            $table->date('fecha_prestamo');
            $table->date('fecha_devolucion_esperada')->nullable();
            $table->date('fecha_devolucion_real')->nullable();
            $table->enum('estado', ['prestado', 'devuelto', 'atrasado'])->default('prestado');
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario_prestamos');
    }
};
