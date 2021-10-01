<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->foreign('offer_id')->references('id')->on('offers')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('source_name', 256);
            $table->string('source_account', 16)->nullable();
            $table->string('source_bank', 256);
            $table->string('source_country', 256);
            $table->string('beneficiary_name', 256);
            $table->string('beneficiary_account', 16)->nullable();
            $table->string('beneficiary_bank', 256);
            $table->string('beneficiary_country', 256);
            $table->string('amount', 13, 2);
            $table->string('reference', 13, 2);
            $table->integer('type');
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
        Schema::dropIfExists('transactions');
    }
}
