<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelsFollowsChannels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels_follows_channels', function (Blueprint $table) {
            $table->string('follower');
            $table->string('followed');
            $table->timestamps();
            $table->foreign('follower')->references('username')->on('users');
            $table->foreign('followed')->references('username')->on('users');
            $table->primary(['follower', 'followed']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels_follows_channels');
    }
}
