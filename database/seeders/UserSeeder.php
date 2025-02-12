<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "username" => "haryc99",
            "password" => Hash::make("test123"),
            "name" => "Hary Capri",
            "token" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76",
        ]);

        User::create([
            "username" => "Bajigur48",
            "password" => Hash::make("test123"),
            "name" => "Hary Capri",
            "token" => "ff6c8f47-7a0c-uui2-bd89-e19c6de3ce76",
        ]);
    }
}
