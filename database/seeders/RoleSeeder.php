<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $customerRole = Role::create(['name' => 'customer']);
        $subscriberRole = Role::create(['name' => 'subscriber']);

        // Assign all permissions to admin
        $adminRole->givePermissionTo([
            'view users',
            'create users',
            'edit users',
            'delete users'
        ]);
    }
}
