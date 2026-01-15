<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:4'
        ]);

        $query = $request->q;
        $user = $request->user();
        $role = DB::table('workspace_user')
            ->where('user_id', $user->id)
            ->value('role');

        if ($role !== 'manager') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        // Limit results to avoid abuse
        $users = DB::table('users')
            ->where('email', 'like', $query . '%')
            ->select('id', 'name', 'email')
            ->limit(10)
            ->get();

        return response()->json($users);
    }
}
