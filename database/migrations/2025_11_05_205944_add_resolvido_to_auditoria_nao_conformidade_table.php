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
        Schema::table('auditoria_nao_conformidade', function (Blueprint $table) {
            $table->string('resolvido')->default('não')->after('nao_conformidade_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auditoria_nao_conformidade', function (Blueprint $table) {
            $table->dropColumn('resolvido');
        });
    }
};
