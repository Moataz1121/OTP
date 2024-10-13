<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PasswordLessController extends Controller
{
    //
    public function store (Request $request){
        $request->validate([
            'email' => ['email' , 'required' ]
        ]);

        $merchant = Merchant::where('email' , $request->email)->first();

        if(!$merchant){
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle')
            ]);
        }

        $merchant->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    public function verify($merchant){
        Auth::guard('merchant')->loginUsingId($merchant);
        return to_route('merchant.index');
    }
}
