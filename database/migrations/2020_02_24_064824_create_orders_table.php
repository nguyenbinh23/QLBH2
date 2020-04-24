<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id')->nullable()->default(null);
            $table->string('kind');
            $table->string('name')->nullable()->default(null);;
            $table->string('address')->nullable()->default(null);;
            $table->string('phone')->nullable()->default(null);;
            $table->string('tax_code')->nullable()->default(null);;
            $table->string('tax')->nullable()->default(null);;
            $table->decimal('total_price',20,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
