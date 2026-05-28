<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        
        // Get all permissions
        $permissions = Permission::all();
        
        // Assign all permissions to admin role
        $adminRole->syncPermissions($permissions);
        
        // Assign limited permissions to user role (you can customize this)
        $userPermissions = Permission::whereIn('name', [
            'dashboard',
            'userprofile',
            'profilepassword'
        ])->get();
        $userRole->syncPermissions($userPermissions);
        
        // Assign admin role to test user
        $testUser = User::where('email', 'test@example.com')->first();
        if ($testUser) {
            $testUser->assignRole('admin');
            echo "Test user assigned admin role\n";
        }
    }
}