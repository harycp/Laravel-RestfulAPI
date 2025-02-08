<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{


    public function testRegisterSuccess()
    {
        $data = [
            'username' => 'Haryc22',
            'password' => "Har220404",
            'name' => "Hary Capri"
        ];

        $this->post('/api/users', $data)
            ->assertStatus(201)
            ->assertJson([
                "data" => [
                    'username' => 'Haryc22',
                    "name"=> "Hary Capri"
                ]
            ]);

        // Log::info($test);
    }
    public function testRegisterFailed()
    {
        $data = [
            'username' => '',
            'password' => "",
            'name' => ""
        ];

        $this->post('/api/users', $data)
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "username" => [
                        "The username field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ],
                    "name" => [
                        "The name field is required."
                    ]
                ]
            ]);
    }
    public function testRegisterUsernameUnique()
    {
        $data = [
            'username' => 'Haryc22',
            'password' => "Har220404",
            'name' => "Hary Capri"
        ];

        $this->post('/api/users', $data)
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "username" => "username has already been taken"
                ]
            ]);
    }
}
