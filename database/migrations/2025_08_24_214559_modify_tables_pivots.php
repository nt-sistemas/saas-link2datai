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
        Schema::table('grupo_grupo_estoque', function (Blueprint $table) {
            $table->uuid('id')->default(Str::uuid())->change();
            //
        });
        Schema::table('grupo_plano_habilitado', function (Blueprint $table) {
            $table->uuid('id')->default(Str::uuid())->change();
            //
        });
        Schema::table('grupo_modalidade_vendas', function (Blueprint $table) {
            $table->uuid('id')->default(Str::uuid())->change();
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};