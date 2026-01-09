<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInvitation;
use App\Services\WorkspaceInvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkspaceUser;

class WorkspaceInvitationController extends Controller
{

    use AuthorizesRequests;

    public function __construct(
        private WorkspaceInvitationService $service
    ) {}

    /**
     * Manager invites a user
     */
    public function invite(Request $request, Workspace $workspace)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:member',
        ]);
        $this->authorize('invite', [$workspace, $request->role]);
        $user = Auth::user();
        $invite = $this->service->invite(
            $workspace,
            $request->email,
            $user,
        );

        return response()->json($invite, 201);
    }

    /**
     * List invitations for logged-in user
     */
    public function myInvites(Request $request)
    {
        $user = Auth::user();

        $invitations = WorkspaceInvitation::with('workspace', 'inviter')
            ->where('invited_by', $user->id)
            ->where('status', 'pending')
            ->get()
            ->groupBy(fn ($invite) => $invite->inviter->id)
            ->map(function ($items) {
                return [
                    'inviter' => $items->first()->inviter,
                    'invitations' => $items->map(fn ($i) => [
                        'id' => $i->id,
                        'invited_user_id' => $i->invited_user_id,
                        'role' => $i->role,
                        'status' => $i->status,
                        'workspace' => $i->workspace,
                        'created_at' => $i->created_at,
                    ]),
                ];
            })
            ->values();

        return response()->json($invitations);

    }

    /**
     * Accept invite
     */
    public function accept(int $id)
    {
        $user = auth()->user();

        $invitation = WorkspaceInvitation::where('id', $id)
            ->where('invited_user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$invitation) {
            return response()->json([
                'message' => 'Invitation not found'
            ], 404);
        }
        // Add user to workspace
        WorkspaceUser::firstOrCreate(
            [
                'workspace_id' => $invitation->workspace_id,
                'user_id' => $user->id,
            ],
            [
                'role' => $invitation->role,
                'status' => 'active',
                'joined_at' => now(),
            ]
        );

        // Mark invitation accepted
        $invitation->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        return response()->json([
            'message' => 'Invitation accepted successfully'
        ]);
    }


    /**
     * Reject invite
     */
    public function reject(WorkspaceInvitation $invite, Request $request)
    {
        $this->authorize('reject', $invite);

        $this->service->reject($invite);

        return response()->json([
            'message' => 'Invitation rejected'
        ]);
    }

    /**
     * Manager removes a member
     */
    public function removeMember(Workspace $workspace, User $user)
    {
        $this->authorize('removeMember', $workspace);

        $this->service->removeMember($workspace, $user);

        return response()->json([
            'message' => 'User removed from workspace'
        ]);
    }
}
