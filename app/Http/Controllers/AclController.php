<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AclController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        User::query()->create($data);
        return successResponse();
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::query()->where('username', '=', $data['username'])->first();
        $this->checkPassword($user, $data['password']);
        $token = $user->createToken('userToken')->plainTextToken;
        return successResponse([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * @param $user
     * @param string $password
     * @throws CustomException
     */
    protected function checkPassword($user, string $password)
    {
        if (!Hash::check($password, $user->password)) {
            throw new CustomException('نام کاربری یا رمز عبور شما اشتباه است.');
        }
    }
}
