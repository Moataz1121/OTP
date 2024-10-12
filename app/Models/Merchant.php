<?php

namespace App\Models;

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
       
    }
}
