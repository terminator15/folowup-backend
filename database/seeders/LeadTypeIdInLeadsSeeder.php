<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadTypeIdInLeadsSeeder extends Seeder
{
    public function run()
    {
        $defaultType = DB::table('lead_types')->where('is_active', true)->first();

        if (!$defaultType) {
            return;
        }

        DB::table('leads')
            ->whereNull('lead_type_id')
            ->update([
                'lead_type_id' => $defaultType->id,
                    'updated_at' => now(),
                ]);
    }
}
