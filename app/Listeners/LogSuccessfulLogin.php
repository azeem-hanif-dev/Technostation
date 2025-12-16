<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Helpers\ActivityLogHelper;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        $user = $event->user;
        ActivityLogHelper::log('Login', 'User', 'User Logged In: ' . $user->email);
    }
}

