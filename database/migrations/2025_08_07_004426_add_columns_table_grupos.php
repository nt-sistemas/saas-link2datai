<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('grupos', function (Blueprint $table) {
            $table->text('grupo_estoque_id')->nullable()->after('description');
            $table->text('plano_habilitado_id')->nullable()->after('grupo_estoque_id');
            $table->text('modalidade_venda_id')->nullable()->after('plano_habilitado_id');
            $table->string('campo_valor_id')->after('modalidade_venda_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grupos', function (Blueprint $table) {
            $table->dropColumn('grupo_estoque');
            $table->dropForeign(['tipo_grupo_id']);
            $table->dropColumn('tipo_grupo_id');
            $table->dropColumn('plano_habilitado');
            $table->dropColumn('modalidade_venda');
            $table->dropColumn('campo_valor');
            //
        });
    }
};
