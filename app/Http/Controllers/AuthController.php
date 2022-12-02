<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\BaseController;
use Validator;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function createUser(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                return BaseController::error($validator->errors(), 'Validation Error', 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            $success['token'] =  $user->createToken('auth_token')->plainTextToken;
            $success['name'] =  $user->name;
        } catch (\Throwable $th) {
            throw $th;
        }

        return BaseController::success(NULL, "Berhasil menambahkan user", 200);
    }
    public function loginUser(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $user = User::where('email', $request->email)->firstOrFail();

            $token = $user->createToken('auth_token')->plainTextToken;

            $accessToken = [
                "accessToken" => $token
            ];

            $result = [
                "sanctum" => $accessToken,
                "user" => $user,
            ];
        } catch (\Throwable $th) {
            throw $th;
        }

        return BaseController::success($result, 'Authorized');
    }
    public function logoutUser()
    {
        try {
            $logout = auth()->user()->tokens()->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
        return BaseController::success("", 'Berhasil logged out');
    }
    public function getProfile()
    {
        $user = auth('sanctum')->user();

        return BaseController::success($user, "Berhasil mengambil data user");
    }
    public function resetToken(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $result = [
            'User' => $user,
            'accessToken' => $user->createToken($user->name)->plainTextToken
        ];

        return BaseController::success($result, 'Berhasil mereset token');
    }
    public function changePassword(Request $request)
    {
        // Validation
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email',
            'newpassword' => 'required'
        ]);

        // Validation fails
        if ($validator->fails()) {
            return BaseController::error($validator->errors(), 'Validation Error', 400);
        }

        $user = User::where('email', '=', request('email'))->first();

        if ($user == NULL) {
            return BaseController::error(NULL, 'User not found', 404);
        }

        try {
            //code...
            $user_change_password = User::find($user->id);
            $user_change_password->password = bcrypt(request('newpassword'));
            $user_change_password->save();

            // return
            return BaseController::success(NULL, 'Sukses merubah password');
        } catch (\Throwable $th) {
            return BaseController::error($th, 'Error', 400);
        }
    }
}
