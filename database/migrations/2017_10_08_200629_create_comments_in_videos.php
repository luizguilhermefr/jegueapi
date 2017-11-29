<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsInVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments_in_videos', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('video_id', 36);
            $table->string('commenter');
            $table->timestamps();
            $table->foreign('video_id')->references('id')->on('videos');
            $table->foreign('commenter')->references('username')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments_in_videos');
    }
}
