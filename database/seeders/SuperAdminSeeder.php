<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::truncate();

        $admins = [
            [
                'firstname' => 'Sarah',
                'lastname' => 'Egwu',
                'email' => 'sarahegwu01@gmail.com',
                'password' => bcrypt('0123456789'),
                'role' => 'superadmin',
            ],
            [
                'firstname' => 'Ose',
                'lastname' => 'Ogbe',
                'email' => 'osememen.ogbe@gmail.com',
                'password' => bcrypt('0123456789'),
                'role' => 'appadmin',
            ]
        ];

        foreach ($admins as $admin) {
            Admin::create($admin);
        }
    }
}
