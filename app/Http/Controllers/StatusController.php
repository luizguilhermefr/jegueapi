<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class StatusController extends Controller
{
    /**
     * Return the API status.
     *
     * @return JsonResponse
     */
    public function checkStatus()
    {
        return response()->json([
           'status' => true,
           'message' => 'JegueStreaming up and running.'
        ], 200);
    }
}