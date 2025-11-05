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
        Schema::table('auditorias', function (Blueprint $table) {
            // Índice para queries de timeline (WHERE created_at >= X ORDER BY created_at)
            $table->index('created_at', 'idx_auditorias_created_at');

            // Índice para queries de distribuição por analista (WHERE analista_responsavel IS NOT NULL GROUP BY analista_responsavel)
            $table->index('analista_responsavel', 'idx_auditorias_analista');

            // Índice composto para queries que filtram por tipo e ordenam por data
            $table->index(['tipo_auditorias_id', 'created_at'], 'idx_auditorias_tipo_created');
        });

        Schema::table('nao_conformidades', function (Blueprint $table) {
            // Índice para queries de não conformidades por tipo de auditoria
            $table->index('tipo_auditoria_id', 'idx_nao_conformidades_tipo');
        });

        // Índice composto na tabela pivot para otimizar contagens
        Schema::table('auditoria_nao_conformidade', function (Blueprint $table) {
            // Índice composto para queries que contam não conformidades por auditoria
            $table->index(['auditoria_id', 'nao_conformidade_id'], 'idx_pivot_auditoria_nc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auditorias', function (Blueprint $table) {
            $table->dropIndex('idx_auditorias_created_at');
            $table->dropIndex('idx_auditorias_analista');
            $table->dropIndex('idx_auditorias_tipo_created');
        });

        Schema::table('nao_conformidades', function (Blueprint $table) {
            $table->dropIndex('idx_nao_conformidades_tipo');
        });

        Schema::table('auditoria_nao_conformidade', function (Blueprint $table) {
            $table->dropIndex('idx_pivot_auditoria_nc');
        });
    }
};
