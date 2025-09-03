<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('keranjang_items', function (Blueprint $table) {
        $table->string('ukuran')->nullable();
        $table->integer('harga')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keranjang_items', function (Blueprint $table) {
            //
        });
    }
};
