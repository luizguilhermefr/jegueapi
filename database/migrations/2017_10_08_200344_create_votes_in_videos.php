<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesInVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes_in_videos', function (Blueprint $table) {
            $table->char('video_id', 36);
            $table->string('voter');
            $table->boolean('upvote');
            $table->timestamps();
            $table->primary(['video_id', 'voter']);
            $table->foreign('voter')->references('username')->on('users');
            $table->foreign('video_id')->references('id')->on('videos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votes_in_videos');
    }
}
