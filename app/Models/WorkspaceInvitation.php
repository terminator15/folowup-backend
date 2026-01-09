<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkspaceInvitation extends Model
{
    protected $fillable = [
        'workspace_id',
        'invited_user_id',
        'invited_by',
        'role',
        'status',
        'expires_at',
    ];

    public function invitedUser()
    {
        return $this->belongsTo(User::class, 'invited_user_id');
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
