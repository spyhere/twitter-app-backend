<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(20)
            ->state(new Sequence(
                ['email_verified_at' => null],
                ['email_verified_at' => Carbon::now()]
            ))
            ->hasProfile(1)
            ->hasPosts(25)
            ->create();

        User::factory()
            ->hasProfile()
            ->hasPosts(25)
            ->create([
                'email' => 'test@gmail.com',
                'password' => 'qweasdzxc',
                'email_verified_at' => Carbon::now()
            ]);
    }
}
