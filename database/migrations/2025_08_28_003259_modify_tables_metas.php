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
        Schema::table('metas_filiais', function (Blueprint $table) {
            $table->foreignUuid('grupo_id')->constrained('grupos')->onDelete('cascade');
        });
        Schema::table('metas_vendedors', function (Blueprint $table) {
            $table->foreignUuid('grupo_id')->constrained('grupos')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metas_filiais', function (Blueprint $table) {
            $table->dropColumn('grupo_id');
        });
        Schema::table('metas_vendedores', function (Blueprint $table) {
            $table->dropColumn('grupo_id');
        });
    }
};
