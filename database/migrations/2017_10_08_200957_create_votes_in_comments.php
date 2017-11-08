<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesInComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes_in_comments', function (Blueprint $table) {
            $table->char('comment_id', 36);
            $table->string('voter');
            $table->boolean('upvote');
            $table->timestamps();
            $table->primary(['comment_id', 'voter']);
            $table->foreign('voter')->references('username')->on('users');
            $table->foreign('comment_id')->references('id')->on('comments_in_videos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votes_in_comments');
    }
}
