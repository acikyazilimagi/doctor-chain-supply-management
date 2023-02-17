<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;

class PasswordResetLinkNotification extends ResetPassword
{
    public function __construct($token)
    {
        parent::__construct($token);
    }

    public function resetUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        return url("/auth/password/reset/{$this->token}?email={$notifiable->getEmailForPasswordReset()}");
    }
}
