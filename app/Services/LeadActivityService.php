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
            'meta' => [
                'from' => $from,
                'to' => $to
            ],
            'type'    => 'status_changed',
            'user_id'   => $user_id,
        ]);
    }
}
