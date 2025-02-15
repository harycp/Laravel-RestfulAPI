<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_1 = User::where('id', 1)->first();
        $user_2 = User::where('id', 2)->first();

        Contact::create([
            "first_name" => "Hary",
            "last_name" => "Capri",
            "email" => "omegahary88@gmail.com",
            "phone" => "08982232323",
            "user_id" => $user_1->id
        ]);

        Contact::create([
            "first_name" => "Cepak",
            "last_name" => "Tebek",
            "email" => "tebakceb88@gmail.com",
            "phone" => "08982032323",
            "user_id" => $user_2->id
        ]);

        for($i = 0; $i <= 10; $i++){
            Contact::create([
                "first_name" => "hary" . $i,
                "last_name" => "belakang" . $i,
                "email" => "email" . $i . ".com",
                "phone" => "08957292" . $i,
                "user_id" => $user_1->id
            ]);
        }
    }
}
