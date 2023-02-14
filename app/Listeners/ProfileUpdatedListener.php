<?php

namespace App\Listeners;

use App\Events\ProfileUpdatedEvent;
use App\Mail\ProfileUpdatedMail;
use Illuminate\Support\Facades\Mail;

class ProfileUpdatedListener
{
    public function handle(ProfileUpdatedEvent $event)
    {
        Mail::to($event->user->email)->send(new ProfileUpdatedMail($event->user));
    }
}
