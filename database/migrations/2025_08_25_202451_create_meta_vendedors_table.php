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
        Schema::create('metas_vendedors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('month', 2);
            $table->year('year');
            $table->foreignUuid('vendedor_id')->constrained('vendedores')->onDelete('cascade');
            $table->decimal('meta', 10, 2)->default(0.00);
            $table->integer('quant')->default(0);
            $table->timestamps();
        });

        Schema::create('meta_vendedor_grupo', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->foreignUuid('vendedor_id')->constrained('vendedores')->onDelete('cascade');
            $table->foreignUuid('meta_grupo_id')->constrained('metas_grupos')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_vendedores');
        Schema::dropIfExists('meta_vendedor_grupo');
    }
};
