<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{


    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            if ($validator->fails()) {
                return $this->sendResponse(false, $validator->errors()->first(), 400);
            }

            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return $this->sendResponse(false, 'Email atau password tidak ditemukan', 401);
            }

            $token =  $this->responseWithToken($token);

            return $this->sendResponseWithDatas($token, 'User logged in successfully!');
        } catch(\Exception $e) {
            return $this->sendResponse(false, $e->getMessage(), 400);
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            $token =JWTAuth::getToken();
            $token = JWTAuth::refresh($token);
            return $this->sendResponseWithDatas($this->responseWithToken($token), 'Token refreshed successfully!');
        } catch(\Exception $e) {
            Log::info($e->getMessage());
            return $this->sendResponse(false, $e->getMessage(), 400);
        }
    }

    public function profile() {
        $user = auth()->user();
        $user = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar_url,
            'role' => $user->role,
        ];
        return response()->json(['user' => $user], 200);
    }

    public function logout()
    {
       try {
            Auth::logout();
            return response()->json(['status' => true, 'message' => 'User logged out successfully!'], 200);
       } catch(\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
       }
    }

    protected function responseWithToken($token)
    {
        $user = Auth::guard('api')->user() ?? Auth::guard('api')->setToken($token)->user();
        $role_has_permission = DB::table("role_has_permissions")
                                    ->where('role_id', $user->role)
                                    ->pluck('permission_id');
        $permissions = Permission::whereIn('id', $role_has_permission)->pluck('name');
        Log::info((array)$permissions);
        Log::info($role_has_permission);
        Log::info($user);

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar_url,
                'permission' => $permissions,
                'role' => $user->role,
            ]
        ];
    }
}
