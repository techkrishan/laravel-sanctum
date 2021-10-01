<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_type_id');
            $table->string('email')->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('full_name', 200);
            $table->string('mobile', 20)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->unsignedBigInteger('user_id')->nullable()->comment('user ID of who create this');
            $table->timestamps();
            $table->foreign('user_type_id')->references('id')->on('lookups');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
