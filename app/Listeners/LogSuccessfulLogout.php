<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Helpers\ActivityLogHelper;

class LogSuccessfulLogout
{
    public function handle(Logout $event)
    {
        $user = $event->user;
        ActivityLogHelper::log('Logout', 'User', 'User Logged Out: ' . ($user ? $user->email : 'Unknown'));
    }
}

