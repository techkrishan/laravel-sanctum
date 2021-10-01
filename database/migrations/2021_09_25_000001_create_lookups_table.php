<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLookupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lookups', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('label', 50);
            $table->string('slug', 50)->unique();
            $table->string('lookup_type', 50);
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_public')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
            $table->index('lookup_type');
            $table->foreign('parent_id')->references('id')->on('lookups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lookups');
    }
}
