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
        Schema::create('auditorias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tipo_auditorias_id')->constrained('tipo_auditorias')->onDelete('cascade');
            $table->string('periodo')->nullable();
            $table->string('quem_criou')->nullable();
            $table->string('analista_responsavel')->nullable();
            $table->decimal('processo');
            $table->decimal('produto');
            $table->string('tarefa_redmine');
            $table->string('nome_do_projeto')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};
