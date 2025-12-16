<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;

class OfferEmailLog extends Model
{
    // Allow mass assignment for these fields
    protected $fillable = [
        'offer_id',
        'offer_type',
        'to_email',
        'subject',
        'pdf_path',
        'email_type',
        'sent_at',
    ];
}

