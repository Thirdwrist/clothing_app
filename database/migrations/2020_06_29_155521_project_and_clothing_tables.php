<?php

use App\Models\Clothe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProjectAndClothingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->string('name');
            $table->integer('cost'); // in kobo
            $table->date('start_date');
            $table->date('end_date');
            $table->char('serial_number')->unique();
            $table->char('event')->default('casual'); // or event
            $table->timestamps();
        });

        Schema::create('clothes', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')
                ->references('id')
                ->on('projects');
            $table->char('sample_image_url');
            $table->char('sample_image_connection');
            $table->enum('gender', [Clothe::MALE, Clothe::FEMALE, Clothe::OTHER]);
            $table->text('description');
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
        Schema::dropIfExists('projects');
        Schema::dropIfExists('clothes');
    }
}
