<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterController extends Controller
{
    public function leadTypes(Request $request)
    {
        $user = $request->user();

        // Get workspace id of user
        $workspaceId = DB::table('workspace_user')
            ->where('user_id', $user->id)
            ->value('workspace_id');

        return DB::table('lead_types')
            ->where('workspace_id', $workspaceId)
            ->where('is_active', true)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }
}
