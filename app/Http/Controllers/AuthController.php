<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $validasi = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:1',
        ]);

        try {
            $data = $this->userService->userRegister($validasi);

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'gagal registrasi, ' . $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validasi = $request->validate([
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:1',
        ]);

        try {
            $data = $this->userService->userLogin($validasi);

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'gagal login, ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        try {
            $this->userService->userLogout();

            return response()->json([
                'status' => 'success',
                'message' => 'berhasil logout'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'gagal logout, ' . $e->getMessage()
            ], 500);
        }
    }

    public function refresh()
    {
        try {
            $data = $this->userService->userRefreshToken();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'gagal refresh token, ' . $e->getMessage()
            ], 500);
        }
    }
}
