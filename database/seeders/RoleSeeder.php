<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = [
            'Admin',
            'User',
        ];
        \App\Models\Role::factory()
            ->count(count($name))
            ->sequence(fn ($sequence) => [
                'name' => $name[$sequence->index],
            ])
            ->create();
    }
}
