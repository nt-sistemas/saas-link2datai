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
        Schema::table('vendedores', function (Blueprint $table) {
            $table->foreignUuid('filial_id')->nullable()->constrained('filials')->onDelete('set null');
            $table->string('avatar')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendedores', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->dropForeign(['filial_id']);
            $table->dropColumn('filial_id');
        });
    }
};
