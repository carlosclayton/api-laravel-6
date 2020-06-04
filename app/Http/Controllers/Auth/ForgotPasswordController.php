<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordUpdateRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Password;
use App\Repositories\UserRepository;
use Prettus\Validator\Exceptions\ValidatorException;

class ForgotPasswordController extends Controller
{
    protected $repository;
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function __construct(UserRepository $repository)
    {
//        $this->middleware('guest');
        $this->repository = $repository;
    }

    /**
     * @OA\Post(
     *      tags={"Autentication"},
     *      path="/api/password",
     *      summary="Change password",
     *      description="Receive an email to change password",
     *      @OA\Parameter(
     *          name="password",
     *          description="Password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       )
     * )
     */
    public function password(PasswordUpdateRequest $request)
    {
        $data['password'] = bcrypt($request->get('password'));
        try {
            $this->repository->update($data, $request->user('api')->id);
            return response()->json([
                'data' => 'Password updated.'
            ]);
        } catch (ValidatorException $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessageBag()
            ]);
        }
    }


    /**
     * @OA\Post(
     *      tags={"Autentication"},
     *      path="/api/password/forgot",
     *      summary="Forgot password",
     *      description="Receive an email to change password",
     *      @OA\Parameter(
     *          name="email",
     *          description="Email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       )
     * )
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);
        try {
            $response = $this->broker()->sendResetLink(
                $this->credentials($request)
            );
        } catch (\Exception $ex) {
            dd($ex);
        }
        if ($response == Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Email sended with successfully'
            ]);
        } else {
            return response()->json([
                'error' => 'Failure to send email,please try again...'
            ]);
        }
    }
}
