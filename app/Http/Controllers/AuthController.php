<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($token = auth('api')->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(UserRequest $request)
    {
        return $this->respondWithToken($this->guard('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = JWTAuth::user();
        return response()->json([
            'access_token' => $token,
            'user' => $this->guard()->user(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function guard()
    {
        return Auth::Guard('api');
    }

    public function register(UserRequest $request)
    {

        $name = $request->name;
        $password = $request->password;
        $password_confirm = $request->password_confirm;
        $email = $request->email;

        if($password == $password_confirm)
        {
            $user = new User;
            $user->name = $name;
            $user->password = Hash::make($password);
            $user->email = $email;
            $user->save();
            return response()->json([
                    'success' => 'Đăng ký thành công'
            ]);
        }
        else {
            return response()->json([
                'errors' => [
                    'password' => ['Mật khẩu không khớp, hãy kiểm tra lại'],
                    'password_confirm' => ['Mật khẩu không khớp, hãy kiểm tra lại']
                    ]
                ],422);
        }
    }

    public function edit(Request $request)
    {
        $this->validate($request,
        [
            'name' => 'required',
            'new_password' => 'max:20|min:8',
        ],
        [
            'name.required' => 'Bạn không được bỏ trống tên',
            'new_password.min' => 'Mật khẩu quá ngắn, cần phải có 8 kí tự trở lên',
            'new_password.max' => 'Mật khẩu quá dài, cần phải có 20 kí tự trở xuống',
        ]);
        $user = User::find($request->id);

        $user_now = JWTAuth::user();
        if($user->id == $user_now->id){
            $user->name = $request->name;
            if($request->picked == true){
                if($request->new_password == $request->password_confirm){
                    $user->password = Hash::make($request->new_password);
                }else
                    return response()->json([
                    'errors' => [
                        'password' => ['Mật khẩu không khớp, hãy kiểm tra lại'],
                        'password_confirm' => ['Mật khẩu không khớp, hãy kiểm tra lại']
                        ]
                    ],422);
            }
            $user->save();
            return response()->json($user,200);
        }
    }
}
