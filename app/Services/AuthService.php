<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\RegisterResource;
use App\Traits\ApiResponse;


class AuthService
{
    use ApiResponse; 

     public function register(array $data)
    {
       $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'phone'    => $data['phone'] ?? null,
            'address'  => $data['address'] ?? null,
            'age'      => $data['age'] ?? null,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

       
        return $this->success(
            new RegisterResource($user, $token),
            'Registered successfully',
            201
        );
    }


    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
           throw $this->error(
    'Login error',
    null,
    401
);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success(
            new LoginResource($user, $token),
            'Login successful'
        );
    }

     public function logout(User $user)
    {
        
        $user->currentAccessToken()->delete();
        
        return $this->success(
            null,
            'Logged out successfully'
        );
    
}
}