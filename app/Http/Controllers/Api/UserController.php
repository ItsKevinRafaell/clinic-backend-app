<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCheckEmailRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index($email) {
        $user = User::where('email', $email)->first();

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    public function updateGoogleId(Request $request, $id) {
        $request->validated();

        $user = User::where('email', $id)->first();
        if ($user) {
            $user->google_id = $request->google_id;
            $user->save();

            return response()->json([
                'status' => 'success',
                'data' => $user,
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'User not found',
            ],404);
        }
    }

    public function update(UserUpdateRequest $request, $id) {
        $request->validated();

        $data = $request->all();
        $user = User::find($id);
        $user->update($data);

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    public function checkEmail(UserCheckEmailRequest $request) {
        $request->validated();

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if($user) {
            return response()->json([
                'status' => 'success',
                'message' => 'Email is already registered',
                'valid' => false,
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Email is not registered',
                'valid' => true,
            ],404);
        }
    }

    public function login(UserLoginRequest $request){
        $request->validated();

        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if(!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Email or password is incorrect',
            ],404);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ], 200);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout successfully',
        ]);
    }

    public function store(UserStoreRequest $request) {
        $request->validated();

        $data = $request->all();
        $name = $request->name;
        $email = $request->email;
        $password = Hash::make($request->password);
        $role = $request->role;

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ], 201);
    }
}
