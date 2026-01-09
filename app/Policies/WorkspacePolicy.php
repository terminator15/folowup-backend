<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workspace;

class WorkspacePolicy
{
    public function invite(User $user, Workspace $workspace, string $role): bool
    {
        // Only managers can invite
        $membership = $workspace->users()
            ->where('user_id', $user->id)
            ->first();

        if (!$membership || $membership->pivot->role !== 'manager') {
            return false;
        }

        // Managers can ONLY invite members
        if ($role !== 'member') {
            return false;
        }

        return true;
    }


    public function removeMember(User $user, Workspace $workspace): bool
    {
        return $user->isManagerOf($workspace->id);
    }

    public function viewMembers(User $user, Workspace $workspace): bool
    {
        return $workspace->users()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }
}

