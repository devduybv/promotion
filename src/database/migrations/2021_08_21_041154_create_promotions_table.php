<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('title');
            $table->longText('content')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('type')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->integer('status')->default(0);
            $table->integer('promo_type_id')->nullable();
            $table->integer('promo_value')->nullable();
            $table->integer('quantity')->nullable();
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
        Schema::dropIfExists('promotions');
    }
}
