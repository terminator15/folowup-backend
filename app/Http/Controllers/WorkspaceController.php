<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WorkspaceController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $workspace = Workspace::create([
            'name' => $request->name,
            'is_team' => true,
        ]);

        // attach creator as manager
        $workspace->users()->attach($request->user()->id, [
            'role' => 'manager',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        return response()->json($workspace, 201);
    }

         
    public function index()
    {
        $user = Auth::user();

        return $user->workspaces()
            ->select('workspaces.id', 'workspaces.name', 'workspace_user.role')
            ->get();
    }

    public function members(Workspace $workspace)
    {
        // Authorization: only members of workspace can view members
        $this->authorize('viewMembers', $workspace);

        return $workspace->users()
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'workspace_user.role',
                'workspace_user.status',
                'workspace_user.joined_at'
            )
            ->get();
    }
}
