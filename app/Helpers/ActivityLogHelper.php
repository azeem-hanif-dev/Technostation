<?php
namespace App\Helpers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\StaffingCompany\ActivityLog;


class ActivityLogHelper
{
    public static function log($action, $model = null, $description = null)
    {
        $user = Auth::user();

        ActivityLog::create([
            'user_id' => $user ? $user->id : null,
            'user_name' => $user ? $user->name : 'System',
            'action' => $action,
            'model' => $model,
            'description' => $description,
            'url' => Request::fullUrl(),
            'ip_address' => Request::ip(),
        ]);
    }
}
