<?php

namespace App\Jobs;

use log;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPersonnelCredentials;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class SendPersonnelEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $password;
    // Maximum attempts for this job
    public $tries = 3;

    // Seconds to wait before retrying after failure
    public $retryAfter = 60;
    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function handle()
    {
        Mail::to($this->email)->send(new SendPersonnelCredentials($this->email, $this->password));
    }

    public function failed(\Throwable $exception)
    {
        // Log the failure or notify admin
        log::error("Failed to send credentials to {$this->email}", [
            'error' => $exception->getMessage()
        ]);
    }
}
