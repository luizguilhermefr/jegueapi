<?php

namespace App\Http\Controllers;

use App\Helpers\Validator;
use App\Video;
use Illuminate\Http\Request;

class VideosController extends Controller
{
    public function create(Request $request){
        Validator::validateString($request->input('name'));
        $video = new Video();
        $video->name = $request->input('name');
        $video->description = $request->input('description');
        $video->owner = $request->user->username;
        $video->category_id = $request->input('category_id');
        $video->save();
        //
    }
}
