<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\User;
use App\Models\LeadActivity;

class LeadActivityService
{
    public function logStatusChange(
        Lead $lead,
        string $from,
        string $to,
        int $user_id
    ): void {
        LeadActivity::create([
            'lead_id'   => $lead->id,
            'from'      => $from,
            'to'        => $to,
            'action'    => 'status_changed',
            'user_id'   => $user_id,
        ]);
    }
}
