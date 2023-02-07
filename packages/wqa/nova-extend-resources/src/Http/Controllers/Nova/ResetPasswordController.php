<?php

namespace Wqa\NovaExtendResources\Http\Controllers\Nova;

use Laravel\Nova\Nova;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ResetPasswordController extends Controller
{
    use ValidatesRequests;
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('nova::auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Get the URI the user should be redirected to after resetting their password.
     *
     * @return string
     */
    public function redirectPath()
    {
        return Nova::path();
    }
}
