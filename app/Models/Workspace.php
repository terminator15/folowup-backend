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
            ])
            ->withTimestamps();
    }

    /**
     * Managers / admins of the workspace
     */
    public function managers(): BelongsToMany
    {
        return $this->users()
            ->wherePivotIn('role', ['manager', 'admin'])
            ->wherePivot('status', 'active');
    }
}
