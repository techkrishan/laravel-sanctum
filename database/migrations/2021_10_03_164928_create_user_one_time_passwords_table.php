<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOneTimePasswordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_one_time_passwords', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('type_id');
            $table->string('otp', 10)->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
            $table->foreign('type_id')->references('id')->on('lookups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_one_time_passwords');
    }
}
