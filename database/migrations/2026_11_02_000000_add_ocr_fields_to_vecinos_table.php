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
        Schema::table('vecinos', function (Blueprint $table) {
            $table->string('comprobante_hash')->nullable()->after('comprobante_domicilio');
            $table->text('comprobante_ocr_text')->nullable()->after('comprobante_hash');
            $table->boolean('comprobante_validado')->nullable()->after('comprobante_ocr_text');
            $table->timestamp('comprobante_validado_at')->nullable()->after('comprobante_validado');
            $table->text('comprobante_motivo')->nullable()->after('comprobante_validado_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vecinos', function (Blueprint $table) {
            $table->dropColumn([
                'comprobante_hash',
                'comprobante_ocr_text',
                'comprobante_validado',
                'comprobante_validado_at',
                'comprobante_motivo',
            ]);
        });
    }
};
