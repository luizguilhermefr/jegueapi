<?php

namespace App\Http\Controllers;

use App\Category;
use App\Exceptions\CategoryNotFoundException;
use App\Helpers\Validator;
use App\Video;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;

class VideosController extends Controller
{
    public function create(Request $request)
    {
        Validator::validateRequired([
            $request->input('name'),
            $request->input('description'),
            $request->input('tags'),
        ]);
        Validator::validateString($request->input('name'));
        Validator::validateString($request->input('description'));
        Validator::validateRequired($request->input('tags'));

        if (! Category::find($request->input('category_id'))) {
            throw new CategoryNotFoundException();
        }

        $video = new Video();
        $video->name = $request->input('name');
        $video->description = $request->input('description');
        $video->owner = $request->user->username;
        $video->category_id = $request->input('category_id');
        $video->save();

        foreach ($request->input('tags') as $tag) {
            $video->pushTag($tag);
        }

        return response()->json([
            'success' => true,
            'id' => $video->id,
        ], 201);
    }
}
