<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth as JWToken;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
    // 
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
            'phone' => 'nullable|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Updated rule for file upload
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        
        $photoPath = null;
        if($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }
        
        User::create([
            'name'=>$request->get('name'),
            'email'=>$request->get('email'),
            'password'=>Hash::make($request->get('password')),
            'role'=>$request->get('role'),
            'phone'=>$request->get('phone'),
            'photo'=>$photoPath, // Stores the path to the uploaded photo
        ]);
        return response()->json(['message' => 'Usuario registrado de manera correcta'], 201);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        $credentials = $request->only('email', 'password');
        try{
            if(!$token = JWToken::attempt($credentials)){
                return response()->json(['error' => 'Credenciales incorrectas'], 401);
            }
            $user = auth()->user();
            return response()->json(['user' => $user, 'token' => $token], 200);
        }
        catch(JWTException $e){
            return response()->json(['error' => 'No se pudo crear el token'], 500);
        }
    }

    public function logout(){
        JWToken::invalidate(JWToken::getToken());
        return response()->json(['message' => 'Sesión cerrada de manera correcta'], 200);
    }

    public function listUsers(){
        $users = User::all();
        return response()->json($users, 200);
    }

    public function updateUser(Request $request, $id){
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'     => 'sometimes|required|string|max:100',
            'email'    => 'sometimes|required|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|string|min:6',
            'role'     => 'sometimes|required|string',
            'phone'    => 'nullable|string',
            'photo'    => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'email', 'role', 'phone']);
        if($request->filled('password')){
            $data['password'] = Hash::make($request->get('password'));
        }
        if($request->hasFile('photo')){
            $photoPath = $request->file('photo')->store('photos', 'public');
            $data['photo'] = $photoPath;
        }
        
        $user->update($data);
        $user->refresh();

        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    public function deleteUser($id){
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function getUser(Request $request){
        $user = auth()->user();
        $user->photo_url = $user->photo ? asset('storage/' . $user->photo) : null;
        return response()->json($user, 200);
    }

}
