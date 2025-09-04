<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // register
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated(); // validasi request data

        // jika cek ke database User, username harus sama dengan data usernama di table, dan jumlahnya 1
        // validasi username harus unique
        if (User::where('username', $data['username'])->count() == 1) {
            // ada di database?
            // kalau gak ada jalankan response ini
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => [
                        "username already registered"
                    ]
                ]
            ], 400));
        }

        $user = new User($data); // ambil database dan harus fillable
        $user->password = Hash::make($data['password']); // hash boss password na
        $user->save(); // terus simpan we

        // return/balikan dalam bentuk UserResource 
        return (new UserResource($user))->response()->setStatusCode(201);
    }

    // login
    public function login(UserLoginRequest $request): UserResource
    {
        $data = $request->validated(); // validasi request data

        // jika cek ke database User, username harus sama dengan data usernama di table, dan data pertama
        // validasi username harus unique
        $user = User::where('username', $data['username'])->first();
        // cek user dan cek data paswword yang di hash nya sesuai dengan di database user kolom password
        if (!$user || !Hash::check($data['password'], $user->password)) {
            // ada di database?
            // kalau gak ada jalankan response ini
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ], 401));
        }

        $user->token = Str::uuid()->toString(); // simpan token dan uuid, konversi ke string
        $user->save(); // terus simpan we

        // return/balikan dalam bentuk UserResource 
        return new UserResource($user);
    }

    // public function get(Request $request): UserResource
    // {
    //     $user = Auth::user();
    //     return new UserResource($user);
    // }

    // public function update(UserUpdaUeequest $request): UserResource
    // {
    //     $data = $request->validated();
    //     $user = Auth::user();

    //     if (isset($data['name'])) {
    //         $user->name = $data['name'];
    //     }

    //     if (isset($data['password'])) {
    //         $user->password = Hash::make($data['password']);
    //     }

    //     $user->save();
    //     return new UserResource($user);
    // }

    // public function logout(Request $request): JsonResponse
    // {
    //     $user = Auth::user();
    //     $user->token = null;
    //     $user->save();

    //     return response()->json([
    //         "data" => true
    //     ])->setStatusCode(200);
    // }
}
