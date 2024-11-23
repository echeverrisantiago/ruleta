<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Seeder;

class SembrarRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Admin',
                'code' => 'admin'
            ],
            [
                'name' => 'Asesor',
                'code' => 'asesor'
            ]
        ];

        foreach ($roles as $rol) {
            Roles::create($rol);
        }
    }
}
