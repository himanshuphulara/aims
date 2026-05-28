<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AiPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'ai.documents.manage',
            'ai.ask',
            'ai.surveys.manage',
            'ai.feedback.view',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web']
            );
        }

        $adminRole = Role::find(1);
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        $this->command->info('AI permissions seeded successfully.');
    }
}
