<?php

namespace Database\Seeders;

use App\Models\News;
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
        $admin = Role::query()->create(['name' => 'admin']);
        $moderator = Role::query()->create(['name' => 'moderator']);
        $author = Role::query()->create(['name' => 'author']);
        $visitor = Role::query()->create(['name' => 'visitor']);

        User::factory()
            ->for($admin)
            ->create([
                "name" => "Admin",
                "email" => "admin@mail.ru",
                "password" => Hash::make('pa$$w0rd'),
            ]);

        // Create 3 authors with one news each
        for ($i = 1; $i <= 3; $i++) {
            $authorUser = User::factory()
                ->for($author)
                ->create([
                    "name" => "Author $i",
                    "email" => "author$i@mail.ru",
                    "password" => Hash::make('pa$$w0rd'),
                ]);

            News::factory()
                ->for($authorUser)
                ->create();
        }
    }
}
