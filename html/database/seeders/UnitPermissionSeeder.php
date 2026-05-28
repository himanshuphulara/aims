<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UnitPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions for units
        $permissions = [
            'unitslist',
            'unitadd',
            'unitedit', 
            'unitupdate',
            'unitdelete'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Assign all unit permissions to admin role (role id = 1)
        $adminRole = Role::find(1);
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        $this->command->info('Unit permissions created and assigned to admin role successfully!');
    }
}
