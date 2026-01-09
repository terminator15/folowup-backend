<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkspaceUser extends Model
{
    protected $table = 'workspace_user'; // IMPORTANT

    protected $fillable = [
        'workspace_id',
        'user_id',
        'role',
        'designation',
        'status',
        'joined_at',
    ];

    public $timestamps = false; // because your table does NOT have created_at/updated_at

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
