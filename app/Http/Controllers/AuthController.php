<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use App\Traits\ApiResponse;
class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(private AuthService $authService) {}
    
    public function register(RegisterRequest $request)
    {
        try{
       $response = $this->authService->register($request->validated());
        return $this->success($response,'register successfully');
        }
        catch(\Exception $e){

            return $this->error($e->getMessage());
        }
    }

    public function login(LoginRequest $request)
    {
 try{
       $response = $this->authService->login($request->validated());
        return $this->success($response,'login successfully');
        }
        catch(\Exception $e){

            return $this->error($e->getMessage());
        }  
      }

    public function logout(Request $request)
    {
 try{
       $response = $this->authService->logout($request);
        return $this->success($response,'logout successfully');
        }
        catch(\Exception $e){

            return $this->error($e->getMessage());
        }   
       }
 

}
