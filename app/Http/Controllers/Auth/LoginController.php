<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Lang;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API RESTFUL Laravel 6.0",
 *      description="API documentation project",
 *      @OA\Contact(
 *          email="carlos.clayton@gmail.com"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */

/**
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     description="Use a global client_id / client_secret and your username / password combo to obtain a token",
 *     name="Authorization",
 *     in="header",
 *     scheme="http",
 *     securityScheme="apiKey"
 * )
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $maxAttempts = 3; // Default is 5
    protected $decayMinutes = 1; // Default is 1

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }

    /**
     * @OA\Post(
     *      tags={"Autentication"},
     *      path="/api/access_token",
     *      summary="Get token",
     *      description="Return token",
     *      @OA\Parameter(
     *          name="email",
     *          description="Email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          description="Password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              format="password"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       )
     * )
     */
    public function accessToken(Request $request)
    {
        $this->validateLogin($request);
        $credentials = $this->credentials($request);
        if ($token = \Auth::guard('api')->attempt($credentials)) {
            return $this->sendLoginResponse($request, $token);
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);

    }

    protected function sendLoginResponse(Request $request, $token)
    {
        return ['token' => $token];
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return response()->json([
            'error' => \Lang::get('auth.failed')
        ], 400);
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );
        $message = Lang::get('auth.throttle', ['seconds' =>
            $seconds]);
        return response()->json([
            'error' => $message
        ], 403);
    }

    /**
     * @OA\Post(
     *      tags={"Autentication"},
     *      path="/api/logout",
     *      summary="Logout ",
     *      description="Logout",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       )
     * )
     */
    public function logout(Request $request)
    {
        \Auth::guard('api')->logout();
        return response()->json([
        ], 204);
    }

    /**
     * @OA\Post(
     *      tags={"Autentication"},
     *      path="/api/refresh_token",
     *      summary="Refresh token",
     *      description="Refresh token",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       )
     * )
     */
    public function refreshToken(Request $request)
    {
        $token = \Auth::guard('api')->refresh();
        return $this->sendLoginResponse($request, $token);
    }
}
