<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\UserRole;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = config('constants.user_roles');
        foreach ($rows as $row) {
            UserRole::updateOrCreate(['slug' => $row['slug']], [
                'label' => $row['label'],
                'is_default' => $row['is_default'],
            ]);
        }
    }
}
