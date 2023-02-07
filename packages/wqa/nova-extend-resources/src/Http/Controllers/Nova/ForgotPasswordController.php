<?php

namespace Wqa\NovaExtendResources\Http\Controllers\Nova;

use Illuminate\Routing\Controller;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    use ValidatesRequests;
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new MailMessage)
                ->line(__('You are receiving this email because we received a password reset request for your account.'))
                ->action(__('Reset Password'), url(config('app.url').route('nova.password.reset', $token, false)))
                ->line(__('If you did not request a password reset, no further action is required.'));
        });
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('nova::auth.passwords.email');
    }
}
