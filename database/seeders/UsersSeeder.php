<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = "Mert Köse";
        DB::table('users')->insert([
            [
                'name' => $name,
                'email' => "mail@mail.com",
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
        User::factory()->count(25)->create();
    }
}
