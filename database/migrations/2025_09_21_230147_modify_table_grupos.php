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
        Schema::table('grupos', function (Blueprint $table) {
            $table->dropColumn('tipo_grupo_id');
        });

        Schema::create('tipo_grupo_grupo', function (Blueprint $table) {
            $table->foreignUuid('tipo_grupo_id')->constrained('tipo_grupos');
            $table->foreignUuid('grupo_id')->constrained('grupos');
        });
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grupos', function (Blueprint $table) {
            $table->foreignUuid('tipo_grupo_id')->constrained('tipo_grupos');
        });
        Schema::dropIfExists('tipo_grupo_grupo');
    }
};
