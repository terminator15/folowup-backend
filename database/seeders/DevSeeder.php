<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DevSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // 1. Create user
            $manager = User::create([
                'name' => 'Manager User',
                'email' => 'manager@followup.com',
                'password' => Hash::make('password123'),
                'registered_at' => now(),
                'password_set_at' => now(),
                'is_active' => true,
            ]);


            $user = User::create([
                'name' => 'Dev User',
                'email' => 'dev6@followup.com',
                'password' => Hash::make('password123'),
                'registered_at' => now(),
                'password_set_at' => now(),
                'is_active' => true,
            ]);

            // 2. Create workspace
            $workspaceId = DB::table('workspaces')->insertGetId([
                'name' => 'Dev Workspace',
                'is_team' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $workspaceId1 = DB::table('workspaces')->insertGetId([
                'name' => 'Manager Workspace',
                'is_team' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Attach user to workspace
            DB::table('workspace_user')->insert([
                'workspace_id' => $workspaceId1,
                'user_id' => $user->id,
                'role' => 'manager',
                'joined_at' => now(),
                'status' => 'active',
            ]);

            // 3. Attach user to workspace
            DB::table('workspace_user')->insert([
                'workspace_id' => $workspaceId,
                'user_id' => $manager->id,
                'role' => 'manager',
                'joined_at' => now(),
                'status' => 'active',
            ]);

            // 4. Create leads
            $lead1 = DB::table('leads')->insertGetId([
                'workspace_id' => $workspaceId,
                'owner_id' => $user->id,   // ✅ ADD THIS
                'name' => 'Rajesh Kumar',
                'phone' => '9876543210',
                'lead_type_id' => 1,
                'deal_value' => 5000000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $lead2 = DB::table('leads')->insertGetId([
                'workspace_id' => $workspaceId,
                'owner_id' => $user->id,   // ✅ ADD THIS
                'name' => 'Amit Sharma',
                'phone' => '9123456789',
                'lead_type_id' => 2,
                'deal_value' => 800000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 5. Lead meta
            DB::table('lead_meta')->insert([
                [
                    'lead_id' => $lead1,
                    'key' => 'bank',
                    'value' => 'HDFC',
                ],
                [
                    'lead_id' => $lead1,
                    'key' => 'tenure',
                    'value' => '20 years',
                ],
                [
                    'lead_id' => $lead2,
                    'key' => 'salary',
                    'value' => '1200000',
                ],
            ]);

        });
    }
}













