<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->only(['user', 'logout']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'min:3', 'email'],
            'password' => ['required', 'min:8'],
        ]);

        try {
            if (Auth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ])) {
                $user = Auth::user();
                $token = $user->createToken('token')->plainTextToken;

                return response()->json([
                    'message' => 'user sign up completed.',
                    'token' => $token
                ], 200);
            } else {
                return response()->json([
                    'error' => 'UnAuthorised',
                    'message' => 'email or password is wrong.'
                ], 401);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Server Error',
            ], 500);
        }
    }

    public function user()
    {
        if (Auth::check()) {
            return response()->json([
                'user' => auth()->user()
            ], 200);
        } else {
            return response()->json([
                'message' => 'not user logging.',
                'error' => 'UnAuthorised'
            ], 401);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:3', 'max:64', 'string'],
            'email' => ['required', 'min:3', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'min:8'],
            'expertises' => ['required', 'array']
        ]);

        try {
            $user = resolve(UserRepository::class)->store($request->only(['name', 'password', 'email', 'expertises', 'expertises', 'is_main_expertise']));

            $token = $user->createToken('token')->plainTextToken;
            return response()->json([
                'message' => 'user sign in completed.',
                'token' => $token,
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'error' => 'Server Error',
            ], 500);
        }
    }

    public function forgetPassword(Request $request)
    {
        $request->validate(['email' => ['required', 'email', 'exists:users,email', 'min:5']]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status) {
            return response()->json([
                'message' => 'send link reset password successfully.',
            ], 200);
        } else {
            return response()->json([
                'error' => 'Server Error',
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed']
        ]);
        try {
            Password::reset($request->only(['email', 'password', 'password_confirmation', 'token']), function (User $user, $password) {

                $user->forceFill([
                    'password' => $password
                ])->setRememberToken(Str::random(60));

                $user->save();
            });
            return response()->json([
                'message' => 'reset password successfully.',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Server Error',
            ], 500);
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            auth()->user()->tokens()->delete();

            return response()->json([
                'message' => 'logout successfully.',
            ], 200);
        } else {
            return response()->json([
                'message' => 'not user for logout.',
                'error' => 'UnAuthorised'
            ], 401);
        }
    }

    public function getTokenResetPassword(Request $request)
    {
        dd($request->token);
    }
}
