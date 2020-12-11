<?php

namespace App\Http\Controllers\API;

use App\Actions\Fortify\PasswordValidationRules;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Exception;
use Facade\FlareClient\Http\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use PasswordValidationRules;

    public function login(Request $request)
    {
        try {
            // validasi input email & passwd 
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            // checking credentials
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 500);
            }

            // check email dan hash password 
            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }

            // jika berhasil maka loginkan
            $tokenresult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenresult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'something went wrong',
                'error' => $error
            ], 'Authenticated Failed', 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'max:255', 'string'],
                'email' => ['required', 'max:255', 'string', 'unique:users'],
                'password' => $this->passwordRules()
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'house_number' => $request->house_number,
                'phone_number' => $request->phone_number,
                'city' => $request->city,
                'password' => Hash::make($request->password)
            ]);

            $user = User::where('email', $request->email)->first();

            $tokenresult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenresult,
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Authentication failed',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success([$token, 'Token Revoked']);
    }

    public function fetch(Request $request)
    {
        return ResponseFormatter::success([
            $request->user(), 'Data Berhasil diambil'
        ]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();

        $user = Auth::User();
        $user->update($data);

        return ResponseFormatter::success([$user, "Profile Updated"]);
    }

    public function updatePhoto(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'file' => 'required|image|max:2048'
            ]
        );

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'error' => $validator->errors()
            ], 'Update Photo Fails', 401);
        }

        if ($request->file('file')) {
            $file = $request->file->store('assets/user', 'public');

            // save ke DB
            $user = Auth::user();
            $user->update_photo_path = $file;
            $user->update();

            return ResponseFormatter::success([$file], 'File Successfully Uploaded');
        }
    }
}
