<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CollectionHasThreads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('collection_has_threads', static function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('collection_id');
            $table->unsignedBigInteger('thread_id');
            $table->timestamps();

            $table->foreign('thread_id')
                ->on('thread')
                ->references('id');
            $table->foreign('collection_id')
                ->on('collection')
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
        //
    }
}
