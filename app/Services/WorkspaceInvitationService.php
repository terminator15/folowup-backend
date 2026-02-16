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
    string $invitedUserEmail,
    User $inviter
    ): array {

        $user = User::where('email', $invitedUserEmail)->first();

        if (! $user) {
            return [
                'success' => false,
                'error' => 'User does not exist in the system'
            ];
        }

        $alreadyMember = $workspace->users()
            ->where([
                'user_id' => $user->id,
                'status' => 'active'
            ])
            ->exists();

        if ($alreadyMember) {
            return [
                'success' => false,
                'error' => 'User is already a member of this workspace'
            ];
        }

        $invite = WorkspaceInvitation::firstOrCreate(
            [
                'workspace_id'    => $workspace->id,
                'invited_user_id' => $user->id,
            ],
            [
                'invited_by' => $inviter->id,
                'role'       => 'member',
                'status'     => 'pending',
            ]
        );

        if (! $invite->wasRecentlyCreated) {
            return [
                'success' => false,
                'error' => 'Invitation already sent'
            ];
        }

        return [
            'success' => true,
            'data' => $invite
        ];
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
