<?php

namespace App\Console\Commands;

use App\Models\EmployAgency;
use App\Models\StaffingCompany\Personnel;
use Illuminate\Console\Command;
use App\Notifications\IdExpiryNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotifyIdExpiry extends Command
{
    protected $signature = 'notify:id-expiry';
    protected $description = 'Send ID Expiry notifications to personnel 15 days before expiry';


    public function handle()
    {
        $this->info('Command is working!');
        $targetDate = Carbon::now()->addDays(15)->toDateString();
        $expiringPersonnels = Personnel::whereDate('id_expiry', $targetDate)
            ->with('agency')
            ->get();
        $grouped = $expiringPersonnels->groupBy('agency_id');
        foreach ($grouped as $agencyId => $personnels) {
            $agency = $personnels->first()->agency;
            if ($agency && $agency->email && filter_var($agency->email, FILTER_VALIDATE_EMAIL)) {
                $agency->notify(new IdExpiryNotification($personnels));
                $personnelNames = $personnels->pluck('first_name')->implode(', ');
                $message = "Notification sent to agency: {$agency->email} for personnels: {$personnelNames}";
                $this->info($message);
                Log::info($message);
            }
        }
        return 0;
    }
}
