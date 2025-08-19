<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use MongoDB\Laravel\Schema\Blueprint as SchemaBlueprint;

return new class extends Migration {
    protected $connection = 'mongodb';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('imports', function (SchemaBlueprint $table) {
            $table->addColumn('string', 'message_error')->nullable();
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('imports', function (Blueprint $table) {
            $table->dropColumn('message_error');
        });
    }
};
