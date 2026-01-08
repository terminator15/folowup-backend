<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workspace;

class WorkspacePolicy
{
    public function invite(User $user, Workspace $workspace): bool
    {
        return $user->isManagerOf($workspace->id);
    }

    public function removeMember(User $user, Workspace $workspace): bool
    {
        return $user->isManagerOf($workspace->id);
    }
}
