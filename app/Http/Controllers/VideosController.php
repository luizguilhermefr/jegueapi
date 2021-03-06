<?php

namespace App\Http\Controllers;

use App\Category;
use App\Exceptions\CategoryNotFoundException;
use App\Exceptions\InvalidExtensionException;
use App\Exceptions\RequiredParameterException;
use App\Exceptions\StringLengthException;
use App\Exceptions\UnauthorizedUserException;
use App\Exceptions\VideoAlreadyUploadedException;
use App\Exceptions\VideoNotFoundException;
use App\Exceptions\VideoNotReadyException;
use App\Helpers\Validator;
use App\Jobs\ParseVideoJob;
use App\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VideosController extends Controller
{
    /**
     * Register a video within the plataform.
     * The upload comes after.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws CategoryNotFoundException
     * @throws RequiredParameterException
     * @throws StringLengthException
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
     * Upload a video.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws UnauthorizedUserException
     * @throws VideoAlreadyUploadedException
     * @throws VideoNotFoundException
     * @throws RequiredParameterException
     * @throws InvalidExtensionException
     */
    public function upload(Request $request, $id)
    {
        $video = Video::find($id);
        if (! $video) {
            throw new VideoNotFoundException();
        }
        if ($video->owner != $request->user->username) {
            throw new UnauthorizedUserException();
        }
        if ($video->playable != null) {
            throw new VideoAlreadyUploadedException();
        }
        if (! $request->hasFile('video')) {
            throw new RequiredParameterException();
        }
        if ($request->file('video')
                ->getMimeType() != 'video/mp4') {
            throw new InvalidExtensionException();
        }

        $path = $request->file('video')
            ->storeAs('videos', "{$video->id}.mp4", 'public');

        dispatch(new ParseVideoJob($video, $path));

        return response()->json(['success' => true], 200);
    }

    /**
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws VideoNotFoundException
     * @throws VideoNotReadyException
     */
    public function watch(Request $request, $id)
    {
        $video = Video::find($id);
        if (! $video) {
            throw new VideoNotFoundException();
        }
        if (! $video->readyToPlay()) {
            throw new VideoNotReadyException();
        }

        $video->load([
            'category' => function ($t) {
                $t->select('id', 'name');
            },
        ]);

        $commentCount = $video->comments()
            ->count();

        return response()->json(['video' => $video, 'comments' => $commentCount], 200);
    }
}
