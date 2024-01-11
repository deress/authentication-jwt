<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
        ]);

        if ($user) {
            // return response()->json(['message' => 'Registrasi Berhasil']);
            return redirect('/')->with('success', 'Registrasi berhasil! Silahkan login');

        } else {
            return response()->json(['message' => 'Registration Gagal']);
        }
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        // $credentials = request(['email', 'password']);

        $validator = Validator::make(request()->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            $error_msg = $validator->errors();
            return redirect('/')->with('loginError', $error_msg);

            // return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return redirect('/')->with('loginError', 'Unauthorized');

            // return response()->json(['error' => 'Unauthorized'], 401);
        }

        return redirect('/home')->with('token', $token);
        // return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    // /**
    //  * Refresh a token.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function refresh()
    // {
    //     return $this->respondWithToken(auth()->refresh());
    // }

    // /**
    //  * Get the token array structure.
    //  *
    //  * @param  string $token
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // protected function respondWithToken($token)
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => auth()->factory()->getTTL() * 60
    //     ]);
    // }
}
