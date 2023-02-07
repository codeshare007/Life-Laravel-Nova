<?php

namespace App\Http\Controllers\Api\CurrentUser;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Exceptions\InvalidApiParameterException;
use App\Exceptions\OriginalPasswordMismatchException;

class CurrentUserPasswordController extends Controller
{
    use ResetsPasswords;

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'original_password' => 'required',
            'new_password' => 'required',
        ]);

        if ($validation->fails()) {
            throw new InvalidApiParameterException($validation->getMessageBag()->getMessages());
        }

        $user = Auth::user();

        if (! Hash::check($request->original_password, $user->password)) {
            throw new OriginalPasswordMismatchException();
        }
        
        $user->password = Hash::make($request->new_password);
        $user->setRememberToken(Str::random(60));
        $user->save();
    }
}
