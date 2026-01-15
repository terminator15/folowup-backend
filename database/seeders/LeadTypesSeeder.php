<?php

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

