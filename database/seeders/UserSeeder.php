<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saltstr = Str::random(10);
        $user_role = Role::where('name', 'admin')->first();
        \App\Models\User::create([
            'name' => 'Admin Admin',
            'email' => 'admin123@email.com',
            'salt' => $saltstr,
            'password' => bcrypt('Admin123' . $saltstr),
            'user_role_id' => $user_role->role_id,
        ]);
        $roles = Role::get();
        foreach ($roles as $rl) {
            $saltstr = Str::random(10);
            \App\Models\User::factory(2)
                ->state([
                    'salt' => $saltstr,
                    'password' => bcrypt($rl->name . '123' . $saltstr),
                    'user_role_id' => $rl->role_id,
                ])
                ->create();
        }
    }
}
