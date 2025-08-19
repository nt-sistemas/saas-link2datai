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
        Schema::create('vendas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->date('data_pedido')->nullable();
            $table->string('numero_pedido')->nullable();
            $table->foreignUuid('tipo_grupo_id')->constrained('tipo_grupos')->onDelete('cascade');
            $table->foreignUuid('filial_id')->constrained('filials')->onDelete('cascade');
            $table->foreignUuid('vendedor_id')->constrained('vendedores')->onDelete('cascade');
            $table->foreignUuid('grupo_estoque_id')->nullable()->constrained('grupo_estoques')->onDelete('cascade');
            $table->foreignUuid('modalidade_venda_id')->nullable()->constrained('modalidade_vendas')->onDelete('cascade');
            $table->foreignUuid('plano_habilitado_id')->nullable()->constrained('plano_habilitados')->onDelete('cascade');
            $table->foreignUuid('grupo_id')->nullable()->constrained('grupos')->onDelete('cascade');
            $table->decimal('valor_total', 10, 2)->default(0.00);
            $table->decimal('base_faturamento_compra', 10, 2)->default(0.00);
            $table->decimal('valor_franquia', 10, 2)->default(0.00);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendas');
    }
};
