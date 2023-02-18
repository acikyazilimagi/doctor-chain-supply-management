<?php

namespace App\Listeners;

use App\Mail\WelcomeMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class RegisteredListener
{
    public function handle(Registered $event)
    {
        Mail::to($event->user->email)->send(new WelcomeMail($event->user));
    }
}
