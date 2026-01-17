<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LeadTypesSeeder extends Seeder
{
    public function run()
    {
        $types = [
            'Personal Loan',
            'Home Loan',
            'Real Estate',
            'Insurance',
            'Credit Card',
        ];

        foreach ($types as $type) {
            DB::table('lead_types')->updateOrInsert(
                ['name' => $type],
                [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

