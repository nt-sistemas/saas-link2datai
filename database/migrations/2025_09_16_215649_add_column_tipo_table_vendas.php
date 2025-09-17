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
        Schema::table('vendas', function (Blueprint $table) {
            $table->string('fabricante')->nullable();
            $table->string('descricao_comercial')->nullable();
            $table->string('categoria')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendas', function (Blueprint $table) {
            $table->dropColumn('fabricante');
            $table->dropColumn('descricao_comercial');
            $table->dropColumn('categoria');
        });
    }
};
