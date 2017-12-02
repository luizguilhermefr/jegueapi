<?php

namespace App\Jobs;

use App\Exceptions\CannotFindVideoException;
use App\Video;

use Illuminate\Support\Facades\Storage;

class ParseVideoJob extends Job
{
    /**
     * @var Video
     */
    private $video;

    /**
     * Create a new job instance.
     *
     * @param Video $video
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $storage = Storage::disk('public');
        if (! $storage->exists("videos/{$this->video->id}.mp4")) {
            $this->fail(new CannotFindVideoException());
        }
    }
}
