<?php
namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function userRegister(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);

        $token = JWTAuth::fromUser($user);

        return[
            'user' => $user,
            'token' => $token,
            'expires_in' => config('jwt.ttl') * 60
        ];
    }

    public function userLogin(array $data)
    {
        $user = Auth::guard('api')->user();

        $token = Auth::guard('api')->attempt($data);

        return[
            'user' => $user,
            'token' => $token,
            'expires_in' => config('jwt.ttl') * 60
        ];
    }

    public function userLogout()
    {
        Auth::guard('api')->logout();
    }

    public function userRefreshToken()
    {
        $token = JWTAuth::parseToken()->refresh();

        return[
            'token' => $token,
            'expires_in' => config('jwt.ttl') * 60
        ];
    }
}