<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubtypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subtypes = $this->getSubtypes();

        foreach ($subtypes as $subtype) {
            DB::table('subtypes')->insert([
                'name' => $subtype, 
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function getSubtypes()
    {
        return [
            'Regular Monster',
            'Effect Monster',
            'Ritual Monster',
            'Quick Play Magic Card',
            'Counter Trap Cards'
        ];
    }
}
