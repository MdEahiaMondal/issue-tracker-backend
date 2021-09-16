<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert([
            [
                'name' => 'Admin Dashboard',
                'slug' => 'admin-dashboard'
            ],
            [
                'name' => 'Developer Dashboard',
                'slug' => 'developer-dashboard'
            ],
            [
                'name' => 'Editor Dashboard',
                'slug' => 'editor-dashboard'
            ],
        ]);
    }
}
