<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Workspace extends Model
{
    protected $fillable = [
        'name',
        'is_team',
    ];

    /**
     * Users belonging to this workspace
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'workspace_user')
            ->withPivot([
                'role',
                'designation',
                'status',
                'joined_at',
            ]);
    }

    /**
     * Managers / admins of the workspace
     */
    public function managers(): BelongsToMany
    {
        return $this->users()
            ->wherePivotIn('role', ['manager'])
            ->wherePivot('status', 'active');
    }

     public function invitations()
    {
        return $this->hasMany(WorkspaceInvitation::class);
    }

     public function members()
    {
        return $this->hasMany(WorkspaceUser::class);
    }
}
