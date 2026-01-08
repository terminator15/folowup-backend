<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInvitation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WorkspaceInvitationService
{
    /**
     * Invite a user by email
     */
    public function invite(
        Workspace $workspace,
        string $email,
        User $inviter
    ): WorkspaceInvitation {
        // Already a member?
        $alreadyMember = $workspace->users()
            ->where('email', $email)
            ->exists();

        if ($alreadyMember) {
            throw ValidationException::withMessages([
                'email' => 'User is already a member of this workspace'
            ]);
        }

        // Existing pending invite?
        $existingInvite = WorkspaceInvitation::where([
            'workspace_id' => $workspace->id,
            'email' => $email,
            'status' => 'pending',
        ])->first();

        if ($existingInvite) {
            throw ValidationException::withMessages([
                'email' => 'Invitation already sent'
            ]);
        }

        return WorkspaceInvitation::create([
            'workspace_id' => $workspace->id,
            'email' => $email,
            'invited_by' => $inviter->id,
            'role' => 'member',
            'status' => 'pending',
        ]);
    }

    /**
     * Accept an invitation
     */
    public function accept(WorkspaceInvitation $invite, User $user): void
    {
        DB::transaction(function () use ($invite, $user) {

            $invite->workspace->users()->attach($user->id, [
                'role' => $invite->role,
                'status' => 'active',
                'joined_at' => now(),
            ]);

            $invite->update([
                'status' => 'accepted'
            ]);
        });
    }

    /**
     * Reject an invitation
     */
    public function reject(WorkspaceInvitation $invite): void
    {
        $invite->update([
            'status' => 'rejected'
        ]);
    }

    /**
     * Remove a member from workspace
     */
    public function removeMember(Workspace $workspace, User $member): void
    {
        $workspace->users()->detach($member->id);
    }
}
