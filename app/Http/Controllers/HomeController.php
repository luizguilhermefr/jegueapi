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
            ->take(3)
            ->get();

        $suggestedChannels = User::has('videos', '>=', 3)
            ->with(['videos' => function ($q) {
                $q->inRandomOrder();
                $q->take(3);
            }])
            ->inRandomOrder()
            ->take(2)
            ->get();

        return response()->json([
            'suggested_videos' => $suggestedVideos,
            'suggested_channels' => $suggestedChannels,
        ], 200);
    }
}