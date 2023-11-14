<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AuthinticationController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {

            $user_data = $request->validated();
            $this->create($user_data);
        } catch (\Exception $e) {

            return response()->json([

                'success' => false,
                'message' => $e->getMessage()

            ], $e->getCode());
        }
        return response()->json([

            'success' => true,
            'message' => 'User Account has been created successfully'

        ], 201);
    }


    public function create(array $data)
    {

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),

        ]);
    }

    public function login(LoginRequest $request)
    {

        if (Auth::attempt($request->validated())) {

            $token = $request->user()->createToken('access-token')->plainTextToken;

            return response()->json([

                'success' => true,
                'token' => $token,
                'message' => 'You have been logged in successfully'
            ], 201);
        } else {

            return response()->json([
                'success' => false,
                'message' => 'Invailed Credentails'
            ], 422);
        }
    }
}
