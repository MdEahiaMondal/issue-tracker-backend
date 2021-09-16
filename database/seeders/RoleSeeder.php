<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            [
                'name' => 'Admin',
                'slug' => 'admin'
            ],
            [
                'name' => 'Developer',
                'slug' => 'developer'
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor'
            ],
        ]);

        $developer_role = Role::developer()->first();
        $d_p = Permission::whereIn('slug', ['developer-dashboard'])->get()->pluck('id')->toArray();
        $developer_role->permissions()->sync($d_p);

    }
}
