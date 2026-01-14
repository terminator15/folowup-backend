<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Google Login
     */
    public function google(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
            'workspace_name' => 'sometimes|nullable|string',
        
        ]);

        // Verify Google token
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->userFromToken($request->id_token);

        if (!$googleUser || !$googleUser->email) {
            return response()->json(['message' => 'Invalid Google token'], 401);
        }

        DB::beginTransaction();

        try {
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                $user = User::create([
                    'name'          => $googleUser->name,
                    'email'         => $googleUser->email,
                    'google_id'     => $googleUser->id,
                    'password'      => bcrypt(Str::random(32)),
                    'registered_at' => now(),
                    'last_login_at' => now(),
                    'is_active'     => true,
                ]);

                if (!empty($request->workspace_name)) {
                    $workspaceId = DB::table('workspaces')->insertGetId([
                        'name'       => $request->workspace_name,
                        'is_team'    => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                 } else {
                    #todo create a default personal workspace for the user
                    // $workspaceId = DB::table('workspaces')->insertGetId([
                    //     'name'       => null,
                    //     'is_team'    => false,
                    //     'created_at' => now(),
                    //     'updated_at' => now(),
                    // ]);
                 }

                DB::table('workspace_user')->insert([
                    'workspace_id' => $workspaceId,
                    'user_id'      => $user->id,
                    'role'         => !empty($request->workspace_name) ? 'manager' : 'member',
                    'joined_at'    => now(),
                    'status'       => 'active',
                ]);
            } else {
                $user->update([
                    'google_id'     => $googleUser->id,
                    'last_login_at' => now(),
                ]);
            }

            DB::commit();

            $token = $user->createToken('auth')->plainTextToken;

            $role = DB::table('workspace_user')
                ->where('user_id', $user->id)
                ->value('role');
            $user->role = $role;
            
            return response()->json([
                'token' => $token,
                'user'  => $user,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Login failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Email + Password login (only if password set)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->password) {
            return response()->json([
                'message' => 'Please login using Google first'
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user->update(['last_login_at' => now()]);

        $token = $user->createToken('auth')->plainTextToken;
        $role = DB::table('workspace_user')
                ->where('user_id', $user->id)
                ->value('role');

        $user->role = $role;
        return response()->json([
            'token' => $token,
            'user'  => $user,
        ]);
    }

    /**
     * Set password after Google login
     */
    public function setPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        $user->update([
            'password'        => Hash::make($request->password),
            'password_set_at' => now(),
        ]);

        return response()->json(['message' => 'Password set successfully']);
    }

    /**
     * Current logged-in user
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
