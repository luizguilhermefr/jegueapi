<?php

namespace App\Http\Controllers;

use App\User;
use App\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Return random channels and vids.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function home(Request $request)
    {
        $suggestedVideos = Video::inRandomOrder()
            ->whereNotNull('playable')
            ->take(3)
            ->get();

        $suggestedChannels = User::has('videos', '>=', 3)
            ->inRandomOrder()
            ->take(3)
            ->get();

        foreach ($suggestedChannels as $channel) {
            $channel->load(['videos' => function ($v) {
               $v->take(3);
            }]);
        }

        return response()->json([
            'suggested_videos' => $suggestedVideos,
            'suggested_channels' => $suggestedChannels,
        ], 200);
    }
}