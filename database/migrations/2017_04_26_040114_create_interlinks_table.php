<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterlinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interlinks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id');
            $table->string('link_reference');
            $table->integer('target_page_id')->nullable();

            $table->unique(['page_id', 'link_reference']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interlinks');
    }
}
