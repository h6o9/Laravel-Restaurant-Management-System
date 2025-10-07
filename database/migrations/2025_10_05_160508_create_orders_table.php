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
        $table->id();
        $table->string('order_no')->unique();
        $table->string('table_no')->nullable();
        $table->unsignedBigInteger('user_id')->nullable(); // cashier/admin
        $table->enum('section', ['kitchen', 'cold_bar'])->default('kitchen'); // section type
        $table->enum('status', ['pending', 'ready', 'completed'])->default('pending');
        $table->text('items'); // JSON: item_name, qty, price etc.
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
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
