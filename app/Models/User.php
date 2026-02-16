<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'google_id',
        'password',
        'registered_at',
        'last_login_at',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password_set_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class, 'workspace_user')
            ->withPivot(['role', 'designation', 'status', 'joined_at']);
    }

    /**
     * MVP assumption:
     * - One active workspace per user
     */
    public function currentWorkspace()
    {
        return $this->workspaces()
            ->wherePivot('status', 'active')
            ->first();
    }

    public function isManager(): bool
    {
        $workspace = $this->currentWorkspace();

        return $workspace
            && in_array($workspace->pivot->role, ['manager', 'admin']);
    }

    public function workspaceMembership(int $workspaceId)
    {
        return $this->workspaces()
            ->where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->first();
    }

    public function isManagerOf(int $workspaceId): bool
    {
        $membership = $this->workspaceMembership($workspaceId);
        return $membership && in_array($membership->pivot->role, ['manager']);
    }

    public function workspaceMemberships()
    {
        return $this->hasMany(WorkspaceUser::class);
    }

    public function sentInvitations()
    {
        return $this->hasMany(WorkspaceInvitation::class, 'invited_by');
    }

    public function receivedInvitations()
    {
        return $this->hasMany(WorkspaceInvitation::class, 'user_id');
    }

}
