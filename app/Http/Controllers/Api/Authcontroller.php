<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Authcontroller extends Controller
{
    public function register(Request $request) {
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $token = $user->createToken('laravel8');
        return response()->json([
            'status' => 200,
            'message' => 'Successfully registered',
            'token' => $token->plainTextToken,
        ]);
    }

    public function login(Request $request) {

        if(Auth::attempt(['email' => $request->email,'password' => $request->password])) {
            $user = auth()->user();

            $token = $user->createToken('laravel8',['user:list']);

            return response()->json([
                'status' => 200,
                'message' => 'Successfully login',
                'token' => $token->plainTextToken,
            ]);
        }
    }
    public function profile(Request $request) {
        
        $user = auth()->user();

        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $user,
        ]);

    }
    public function userList(Request $request) {
        
        if(!auth()->user()->tokenCan('user:list')) {
            return response()->json([
                'status' => 403,
                'message' => 'Unauthorized', 
            ]);
    

        }
        $users = User::all();
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $users,
        ]);
    }
}
