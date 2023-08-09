<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin_user = \App\Models\User::create([
            'name'  => 'Marlon Castillo',
            'email' => 'hello@marloncastillo.dev',
            'password' => bcrypt('faMiLi4!'),
        ]);

        $global_team = $admin_user->ownedTeams()->create([
            'user_id' => $admin_user->id,
            'name' => config('constants.global_team')['name'],
            'personal_team' => config('constants.global_team')['personal_team'],
        ]);

        $admin_user->teams()->attach($global_team, ['role' => 'admin']);
        $admin_user->switchTeam($global_team);
    }
}
