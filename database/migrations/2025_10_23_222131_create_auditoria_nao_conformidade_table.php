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
        Schema::create('auditoria_nao_conformidade', function (Blueprint $table) {
            $table->uuid('auditoria_id');
            $table->uuid('nao_conformidade_id');
            $table->timestamps();

            $table->primary(['auditoria_id', 'nao_conformidade_id']);

            $table->index('auditoria_id');
            $table->index('nao_conformidade_id');

            $table->foreign('auditoria_id')
                ->references('id')
                ->on('auditorias')
                ->onDelete('cascade');

            $table->foreign('nao_conformidade_id')
                ->references('id')
                ->on('nao_conformidades')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditoria_nao_conformidade');
    }
};
