<?php

namespace App\Http\Controllers;

use App\Exceptions\EmailAlreadyTakenException;
use App\Exceptions\InvalidEmailException;
use App\Exceptions\InvalidLoginException;
use App\Exceptions\PasswordConfirmationException;
use App\Exceptions\RequiredParameterException;
use App\Exceptions\StringLengthException;
use App\Exceptions\UsernameAlreadyTakenException;
use App\Exceptions\UserNotFoundException;
use App\Helpers\Validator;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Register an user within the plataform.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws EmailAlreadyTakenException
     * @throws InvalidEmailException
     * @throws PasswordConfirmationException
     * @throws RequiredParameterException
     * @throws StringLengthException
     * @throws UsernameAlreadyTakenException
     */
    public function register(Request $request)
    {
        Validator::validateRequired([
            $request->input('email'),
            $request->input('username'),
            $request->input('password'),
            $request->input('description'),
        ]);
        Validator::validateString($request->input('description'));
        Validator::validateEmail($request->input('email'));
        Validator::validateUsername($request->input('username'));
        Validator::validatePassword($request->input('password'), $request->input('password_confirmation'));
        $user = new User();
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = hash('sha256', $request->input('password'));
        $user->description = $request->input('description');
        $user->saveAndGenerateToken();

        return response()->json([
            'success' => true,
            'user' => $user->makeVisible('remember_token'),
        ], 201);
    }

    /**
     * Enter the plataform.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws InvalidLoginException
     */
    public function login(Request $request)
    {
        $user = User::findByEmailAndPassword($request->input('email'), $request->input('password'));
        if (is_null($user)) {
            throw new InvalidLoginException();
        }

        return response()->json([
            'success' => true,
            'user' => $user->makeVisible('remember_token'),
        ]);
    }

    /**
     * Get some user profile.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws UserNotFoundException
     */
    public function profile(Request $request, $id)
    {
        $user = User::find($id);
        if (! $user) {
            throw new UserNotFoundException();
        }

        $follows = $user->follows()
            ->select('username', 'description')
            ->take(10)
            ->get();

        $followers = $user->followers()
            ->select('username', 'description')
            ->take(10)
            ->get();

        $videos = $user->videos()
            ->select('id', 'name', 'created_at', 'category_id', 'thumbnail')
            ->with(['category' => function ($t) {
               $t->select('id', 'name');
            }])
            ->whereNotNull('playable')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'user' => $user,
            'follows' => $follows,
            'followers' => $followers,
            'videos' => $videos,
        ], 200);
    }
}
