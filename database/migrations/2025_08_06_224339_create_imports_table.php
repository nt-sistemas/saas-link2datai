<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint as SchemaBlueprint;

return new class () extends Migration {
    protected $connection = 'mongodb';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('imports', function (SchemaBlueprint $table) {
            $table->id();
            $table->uuid('tenant_id')->index();
            $table->date('data_pedido')->nullable();
            $table->string('numero_pedido')->nullable();
            $table->json('data')->nullable();
            $table->boolean('is_processed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
