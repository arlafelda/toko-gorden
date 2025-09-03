<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStokToProductSizesTable extends Migration
{
    public function up()
    {
        Schema::table('product_sizes', function (Blueprint $table) {
            $table->integer('stok')->default(0)->after('harga');
        });
    }

    public function down()
    {
        Schema::table('product_sizes', function (Blueprint $table) {
            $table->dropColumn('stok');
        });
    }
}
