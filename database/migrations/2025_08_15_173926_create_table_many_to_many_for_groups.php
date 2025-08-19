<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grupo_grupo_estoque', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('group_id')->constrained('grupos')->onDelete('cascade');
            $table->foreignUuid('grupo_estoque_id')->constrained('grupo_estoques')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('grupo_plano_habilitado', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('group_id')->constrained('grupos')->onDelete('cascade');
            $table->foreignUuid('grupo_plano_habilitado_id')->constrained('plano_habilitados')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('grupo_modalidade_vendas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('group_id')->constrained('grupos')->onDelete('cascade');
            $table->foreignUuid('modalidade_venda_id')->constrained('modalidade_vendas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_grupo_estoque');
        Schema::dropIfExists('grupo_plano_habilitado');
        Schema::dropIfExists('grupo_modalidade_vendas');
    }
};
