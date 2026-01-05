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
        Schema::table('modalidade_vendas', function (Blueprint $table) {
            $table->string('name')->change();
        });

        Schema::table('plano_habilitados', function (Blueprint $table) {
            $table->string('name')->change();
        });

        Schema::table('grupo_estoques', function (Blueprint $table) {
            $table->string('name')->change();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};