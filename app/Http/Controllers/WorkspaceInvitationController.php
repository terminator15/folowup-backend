<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInvitation;
use App\Services\WorkspaceInvitationService;
use Illuminate\Http\Request;

class WorkspaceInvitationController extends Controller
{
    public function __construct(
        private WorkspaceInvitationService $service
    ) {}

    /**
     * Manager invites a user
     */
    public function invite(Request $request, Workspace $workspace)
    {
        $this->authorize('invite', $workspace);

        $data = $request->validate([
            'email' => 'required|email'
        ]);

        $invite = $this->service->invite(
            $workspace,
            $data['email'],
            $request->user()
        );

        return response()->json($invite, 201);
    }

    /**
     * List invitations for logged-in user
     */
    public function myInvites(Request $request)
    {
        return WorkspaceInvitation::with('workspace', 'inviter')
            ->where('email', $request->user()->email)
            ->where('status', 'pending')
            ->get();
    }

    /**
     * Accept invite
     */
    public function accept(WorkspaceInvitation $invite, Request $request)
    {

        $this->authorize('accept', $invite);

        $this->service->accept($invite, $request->user());

        return response()->json([
            'message' => 'Invitation accepted'
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
