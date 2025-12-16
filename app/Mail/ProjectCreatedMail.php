<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\StaffingCompany\StaffingProject;

class ProjectCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $project;

    public function __construct(StaffingProject $project)
    {
        $this->project = $project;
    }

    public function build()
{
    return $this->from('noreply@technostation.org', 'Digital Clean Solution')
                ->subject('New Project Created')
                ->view('emails.project_created');
}
}
