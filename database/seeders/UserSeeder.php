<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::query()->where("name", "admin")->first();
        User::factory()
            ->for($admin)
            ->create([
                "name" => "Admin",
                "email" => "admin@mail.ru",
                "password" => Hash::make('pa$$w0rd'),
            ]);
    }
}
