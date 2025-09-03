<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('product_name');
            $table->unsignedBigInteger('produk_id')->after('order_id');
            $table->string('ukuran')->nullable()->after('produk_id');
            $table->integer('jumlah')->after('ukuran');
            $table->integer('harga')->after('jumlah');
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['produk_id', 'ukuran', 'jumlah', 'harga']);
            $table->string('product_name')->after('order_id');
        });
    }
};
