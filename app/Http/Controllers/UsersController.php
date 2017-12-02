<?php

namespace App\Http\Controllers;

use App\Exceptions\EmailAlreadyTakenException;
use App\Exceptions\InvalidEmailException;
use App\Exceptions\InvalidLoginException;
use App\Exceptions\PasswordConfirmationException;
use App\Exceptions\RequiredParameterException;
use App\Exceptions\StringLengthException;
use App\Exceptions\UsernameAlreadyTakenException;
use App\Helpers\Validator;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Registra um usuário na plataforma.
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
            'url' => $user->getChannelUrl(),
            'token' => $user->getRememberToken()
        ], 201);
    }

    /**
     * Entra na plataforma.
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
           'token' => $user->getRememberToken()
        ]);
    }
}
