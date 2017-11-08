<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidLoginException;
use App\Helpers\Validator;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
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
            'url' => $user->getChannelUrl(),
            'token' => $user->getRememberToken()
        ], 201);
    }

    public function login(Request $request)
    {
        $user = User::findByEmailAndPassword($request->input('email'), $request->input('password'));
        if (is_null($user)) {
            throw new InvalidLoginException();
        }

        return response()->json([
           'success' => true,
           'token' => $user->getRememberToken()
        ]);
    }
}
