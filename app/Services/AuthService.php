<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthService
{
    

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

       
        return [
            $user,$token
        ];
    }


    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
           throw "error";
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [$user,$token];
    }

     public function logout(Request $request)
    {
        
        $request->user()->currentAccessToken()->delete();
        
        return "ok";
    
}
}