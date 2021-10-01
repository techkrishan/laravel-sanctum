<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAnswerRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_answer_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('to_user_id')->comment('who receive the rating');
            $table->unsignedBigInteger('from_user_id')->comment('who give the rating');
            $table->unsignedInteger('rating_id');            
            $table->timestamps();
            $table->foreign('question_id')->references('id')->on('questions');
            $table->foreign('to_user_id')->references('id')->on('users');
            $table->foreign('from_user_id')->references('id')->on('users');
            $table->foreign('rating_id')->references('id')->on('lookups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_answer_ratings');
    }
}
