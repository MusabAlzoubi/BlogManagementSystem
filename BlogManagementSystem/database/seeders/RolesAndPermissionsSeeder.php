<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        if (!Role::where('name', 'Admin')->exists()) {
            Role::create(['name' => 'Admin']);
        }

        if (!Role::where('name', 'Writer with Approval')->exists()) {
            Role::create(['name' => 'Writer with Approval']);
        }

        if (!Role::where('name', 'Writer without Approval')->exists()) {
            Role::create(['name' => 'Writer without Approval']);
        }

        $user = \App\Models\User::find(1); 
        if ($user && !$user->hasRole('Admin')) {
            $user->assignRole('Admin');
        }
    }
}
