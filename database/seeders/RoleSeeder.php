<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'HRD']);
        $role_staff = Role::create(['name' => 'Manager OPS']);
        $role_staff = Role::create(['name' => 'Staff']);



    }
}
