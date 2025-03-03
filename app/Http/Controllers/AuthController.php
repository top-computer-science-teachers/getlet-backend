<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Presenters\JsonPresenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $phone = normalizePhone($data['phone']);
        $password = $data['password'];

        $user = User::query()->where('phone', $phone)->first();
        if (!$user) {
            return JsonPresenter::make()
                ->setError('User not found')
                ->setStatusCode(404)
                ->respond();
        }

        if (!Hash::check($password, $user->password)) {
            return JsonPresenter::make()
                ->setError('Credentials incorrect')
                ->setStatusCode(400)
                ->respond();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $res = [
            'token_type' => 'Bearer',
            'access_token' => $token,
        ];

        return JsonPresenter::make()
            ->setData($res)
            ->setStatusCode(200)
            ->respond();
    }
}
