<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ThreadComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('comments', static function (Blueprint $table) {
            $table->id();
            $table->longText('comment');
            $table->unsignedBigInteger('thread_id');
            $table->unsignedBigInteger('comment_id')->nullable();
            $table->timestamps();

            $table->foreign('thread_id')
                ->on('threads')
                ->references('id');
            $table->foreign('comment_id')
                ->on('comments')
                ->references('id');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
