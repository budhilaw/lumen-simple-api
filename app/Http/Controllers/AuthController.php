<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    private $request;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request  = $request;
    }

    protected function jwt(User $user)
    {
        $payload    = [
            'iss'   => 'lumen-jwt',
            'sub'   => $user->id,
            'iat'   => time(),
            'exp'   => time() + 60*60
        ];

        return JWT::encode($payload, env('JWT_SECRET'));
    }

    public function authenticate(User $user)
    {
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $user   = User::where('email', $this->request->input('email'))->first();

        if( !$user )
        {
            return response()->json([
                'error' => "Email doesn't exist."
            ], 400);
        }

        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'token' => $this->jwt($user)
            ], 200);
        }

        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }
}
