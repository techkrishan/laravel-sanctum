<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Lookup;

class LookupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = config('lookups');
        foreach ($rows as $lookupType => $types) {
            foreach($types as $row) {
                Lookup::updateOrCreate(['lookup_type' => $lookupType, 'slug' => $row['slug']], [
                    'label'         => $row['label'],
                    'is_default'    => $row['is_default'] ?? 0,
                    'is_public'     => $row['is_public'] ?? 1,
                    'sort_order'    => $row['sort_order'] ?? 0,
                ]);
            }            
        }
    }
}
