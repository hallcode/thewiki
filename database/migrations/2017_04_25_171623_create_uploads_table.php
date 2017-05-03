<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('reference')->unique();
            $table->string('type');
            $table->string('path')->unique();
            $table->text('caption')->nullable();
            $table->text('meta')->nullable();
            $table->integer('folder_id')->nullable();
            $table->integer('uploaded_by_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('attachable', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('upload_id');
            $table->morphs('attachable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploads');
        Schema::dropIfExists('attachable');
    }
}
