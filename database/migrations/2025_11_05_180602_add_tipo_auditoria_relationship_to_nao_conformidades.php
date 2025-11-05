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
        Schema::table('nao_conformidades', function (Blueprint $table) {
            $table->dropColumn('tipo_de_nao_conformiade');
            $table->uuid('tipo_auditoria_id')->nullable();
            $table->foreign('tipo_auditoria_id')
                ->references('id')
                ->on('tipo_auditorias')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nao_conformidades', function (Blueprint $table) {
            $table->dropForeign(['tipo_auditoria_id']);
            $table->dropColumn('tipo_auditoria_id');
            $table->string('tipo_de_nao_conformiade')->nullable();
        });
    }
};
