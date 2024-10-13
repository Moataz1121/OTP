<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;

class Merchant extends Authenticatable implements MustVerifyEmail
{
    use HasFactory , Notifiable;
    protected $guarded = ['id'];
    
    
    public function sendEmailVerificationNotification()
    {
        if(config('verify.way') == 'email'){
            $url = URL::temporarySignedRoute(
                'merchant.verification.verify',
                now()->addMinutes(60),
                ['id' => $this->getKey(),
                'hash' => sha1($this->getEmailForVerification())
                ]
            );
            $this->notify(new \App\Notifications\MerchantMessage($url));
        }


        if(config('verify.way') == 'passwordless'){
            $url = URL::temporarySignedRoute(
                'merchant.login.verify',
                now()->addMinutes(60),
                ['merchant' => $this->getKey(),
                ]
            );
            $this->notify(new \App\Notifications\MerchantMessage($url));
        }

        if(config('verify.way') == 'cvt'){
            $this->generateVerificationToken();
            $url = route(
                'merchant.verification.verify', [
                'id' => $this->getKey(),
                'token' => $this->verification_token,
            ]);
            $this->notify(new \App\Notifications\MerchantMessage($url));
        }
       
    }



    // ================For CVT ================
    public function generateVerificationToken() {
        if(config('verify.way') == 'cvt'){  // Fix the comparison here
            $this->verification_token = \Illuminate\Support\Str::random(40); // Generate random token
            $this->verification_token_till = now()->addMinutes(60); // Fix the typo in variable name
            $this->save(); // Save the token and its expiration time
        }
    }
    

    public function verifyUsingVerificationToken(){
        if(config('verify.way' == 'cvt')){
            $this->email_verified_at = now();
            $this->verification_token = null;
            $this->verification_token_till = null;
            $this->save();
        }
    }


    // ==================End CVT ================
}
