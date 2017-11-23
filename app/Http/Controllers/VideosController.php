<?php

namespace App\Http\Controllers;

use App\Category;
use App\Exceptions\CategoryNotFoundException;
use App\Exceptions\UnauthorizedUserException;
use App\Exceptions\VideoAlreadyUploadedException;
use App\Exceptions\VideoNotFoundException;
use App\Helpers\Validator;
use App\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VideosController extends Controller
{
    /**
     * Efetua o cadastro de um vídeo. O vídeo é enviado posteriormente.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws CategoryNotFoundException
     */
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

    /**
     * @param Request $request
     * @param int $id
     * @throws UnauthorizedUserException
     * @throws VideoAlreadyUploadedException
     * @throws VideoNotFoundException
     */
    public function upload(Request $request, $id)
    {
        $video = Video::find($id);
        if (!$video) {
            throw new VideoNotFoundException();
        }
        if ($video->owner != $request->user->username) {
            throw new UnauthorizedUserException();
        }
        if ($video->playable != null) {
            throw new VideoAlreadyUploadedException();
        }
    }
}
