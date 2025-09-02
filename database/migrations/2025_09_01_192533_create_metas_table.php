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
        Schema::create('metas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->nullable()->constrained('tenants');
            $table->foreignUuid('grupo_id')->nullable()->constrained('grupos');
            $table->foreignUuid('filial_id')->nullable()->constrained('filials');
            $table->foreignUuid('vendedor_id')->nullable()->constrained('vendedores');
            $table->integer('ano');
            $table->integer('mes');
            $table->decimal('valor_meta', 15, 2)->nullable();
            $table->integer('quantidade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metas');
    }
};
