<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ambil token dari request header yang Authorization
        $token = $request->header('Authorization');
        $authenticate = true; // cek authenticate harus true

        // jika token gak ada
        if (!$token) {
            // authenticate jadi false
            $authenticate = false;
        }

        // cek ke database, apakah token ada di database?, ambil dari pertama
        $user = User::where('token', $token)->first();
        // kalau gak ada user
        if (!$user) {
            // set authenticate jadi false
            $authenticate = false;
        } else {
            // Auth method login, untuk registrasikan usernya 
            Auth::login($user);
        }

        // kalau di ter authentikasi, lanjutkan ke controller lainnya 
        if ($authenticate) {
            return $next($request);
        } else {
            // kalau gak berikan response error
            return response()->json([
                "errors" => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
            ])->setStatusCode(401);
        }
    }
}
