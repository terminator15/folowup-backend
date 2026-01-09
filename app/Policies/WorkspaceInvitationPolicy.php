<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkspaceInvitation;

class WorkspaceInvitationPolicy
{
    /**
     * Can the user view this invitation?
     */
    public function view(User $user, WorkspaceInvitation $invite): bool
    {
        return $user->email === $invite->email;
    }

    /**
     * Can the user accept this invitation?
     */
    public function accept(User $user, WorkspaceInvitation $invite): bool
    {
        return $invite->invited_user_id === $user->id
        && $invite->status === 'pending';
    }

    /**
     * Can the user reject this invitation?
     */
    public function reject(User $user, WorkspaceInvitation $invite): bool
    {
        return $this->accept($user, $invite);
    }
}
