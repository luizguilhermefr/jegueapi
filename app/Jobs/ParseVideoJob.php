<?php

namespace App\Jobs;

use App\Exceptions\CannotFindVideoException;
use App\Exceptions\UnparseableVideoException;
use App\Video;

use Illuminate\Support\Facades\Storage;

class ParseVideoJob extends Job
{
    /**
     * @var Video
     */
    private $video;

    /**
     * @var string
     */
    private $videoUrl;

    /**
     * Create a new job instance.
     *
     * @param Video $video
     * @param string $videoUrl
     */
    public function __construct(Video $video, string $videoUrl)
    {
        $this->video = $video;
        $this->videoUrl = $videoUrl;
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

        $videoPath = storage_path("app/public/videos/{$this->video->id}.mp4");

        $thumbnailPath = storage_path("app/public/videos/thumbnails/{$this->video->id}.jpg");

        $second = 1;

        $thumbSize = '750x450';

        $cmd = "ffmpeg -i {$videoPath} -deinterlace -an -ss {$second} -t 00:00:01  -s {$thumbSize} -r 1 -y -vcodec mjpeg -f mjpeg {$thumbnailPath} 2>&1";

        exec($cmd, $output, $retval);

        if ($retval) {
            $this->fail(new UnparseableVideoException());
        }

        $this->video->setPlayable($this->videoUrl)
            ->setThumbnail("videos/thumbnails/{$this->video->id}.jpg")
            ->save();
    }
}
