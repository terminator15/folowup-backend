<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    /**
     * View lead
     */
    public function view(User $user, Lead $lead): bool
    {
        // Owner always allowed
        if ($lead->owner_id === $user->id) {
            return true;
        }

        // Manager/Admin of workspace
        return $user->isManagerOf($lead->workspace_id);
    }

    /**
     * Update lead
     */
    public function update(User $user, Lead $lead): bool
    {
        // Owner can update basic fields
        if ($lead->owner_id === $user->id) {
            return true;
        }
        return false;
    }

    /**
     * Delete lead
     */
    public function delete(User $user, Lead $lead): bool
    {
        return $lead->owner_id === $user->id;
    }
}
