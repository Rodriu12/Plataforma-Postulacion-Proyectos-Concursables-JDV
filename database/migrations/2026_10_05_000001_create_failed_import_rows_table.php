<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Publicada manualmente desde vendor/filament/actions/database/migrations
 * — depende de 'imports', debe correr después de esa.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('failed_import_rows', function (Blueprint $table): void {
            $table->id();
            $table->json('data');
            $table->foreignId('import_id')->constrained()->cascadeOnDelete();
            $table->text('validation_error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('failed_import_rows');
    }
};
