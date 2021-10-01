<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('selling_currency_id');
            $table->foreign('selling_currency_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('buying_currency_id');
            $table->foreign('buying_currency_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('beneficiary_id');
            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('amount', 13, 2);
            $table->decimal('rate', 13, 2);
            $table->integer('status');
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
        Schema::dropIfExists('bids');
    }
}
