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

        $this->post('/api/users/register', $data)
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

        $this->post('/api/users/register', $data)
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

        $this->post('/api/users/register', $data)
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "username" => "username has already been taken"
                ]
            ]);
    }

    public function testLoginSuccess()
    {
        $data = [
            "username" => "haryc99",
            "password" => "test123"
        ];

        $this->post('/api/users/login', $data)
             ->assertStatus(200)
             ->assertJson([
                "data" => [
                    "username" => "haryc99",
                    "name" => "Hary Capri"
                ]
                ]);
    }

    public function testLoginFailed()
    {
        $data = [
            'username' => "",
            'password' => "",
        ];

        $this->post('/api/users/login', $data)
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "username" => ["The username field is required."],
                        "password" => ["The password field is required."]
                    ]
                ]
                    ]);
           
    }
    public function testLoginFialedNotFound()
    {
        $data = [
            'username' => "hary9",
            'password' => "test123",
        ];

        $this->post('/api/users/login', $data)
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "username" => ["The username field is required."],
                        "password" => ["The password field is required."]
                    ]
                ]
                    ]);
           
    }

    public function testGetSuccess()
    {
        $this->get('/api/users/current', [
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "username" => "haryc99",
                    "name" => "Hary Capri"
                ]
                ]);
    }
    public function testTokenUnauthorized()
    {
        $this->get('/api/users/current')
           ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
                    ]);
    }
    public function testTokenInvalid()
    {
        $this->get('/api/users/current', [
            "Authorization" => "Slebew"
        ])
           ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
                ]);
    }
}
