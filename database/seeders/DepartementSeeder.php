<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Staff;
use App\Models\Departement;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departement = Departement::create([
            'nama'=> 'DIVISI OPERASIONAL',
        ]);

        $admin = User::factory()->create([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '123456780',
        ]);
        $staff = Staff::create([
            'user_id' => $admin->id,
            'nama' => $admin->name,
            'jabatan' => "HRD",
            'departement_id' => $departement->id,
            'alamat' => 'Makassar',
        ]);

        $admin->assignRole('HRD');
        $admin->givePermissionTo([
            'add staff',
            'edit staff',
            'delete staff',
            'show staff',

            'add kriteria',
            'edit kriteria',
            'delete kriteria',
            'show kriteria',
            'reset kriteria',

            'add aspek',
            'edit aspek',
            'delete aspek',
            'show aspek',

            'add departement',
            'edit departement',
            'delete departement',
            'show departement',

            'add penilaian',
            'edit penilaian',
            'delete penilaian',
            'show penilaian',
        ]);

        User::factory(10)->afterCreating(function (User $user) use($departement) {
            $role = Role::findByName('Staff'); // Replace 'user' with your actual role name
            if ($role) {
                $user->assignRole($role); // Assign 'user' role to the user
            }
            $user->givePermissionTo([
                'show penilaian',
                'show staff',
            ]);
            Staff::create([
                'jabatan' => $role->name,
                'user_id' => $user->id,
                'departement_id' => $departement->id,
                'nama' => $user->name,
                'alamat' => fake()->address(),
            ]);
        })->create();
    }
}
