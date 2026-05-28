<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class VoucherpPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create voucherp permissions
        $permissions = [
            'voucherp',
            'voucherpadd',
            'voucherpedit',
            'voucherpupdate',
            'voucherpdelete'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ], [
                'type' => 'Voucher'
            ]);
        }

        $this->command->info('Voucherp permissions created successfully!');
    }
}
