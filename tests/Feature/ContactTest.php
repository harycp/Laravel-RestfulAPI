<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    public function testCreateSuccess()
    {
        $data = [
            "first_name" => "Hary",
            "last_name" => "Capri",
            "email" => "omegahary88@gmail.com",
            "phone" => "089521234982"
        ];

        $this->post('/api/contacts', $data, [
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
        ])->assertStatus(201)
         ->assertJson([
            "data" => [
                "first_name" => "Hary",
                "last_name" => "Capri",
                "email" => "omegahary88@gmail.com",
                "phone" => "089521234982"
            ]
            ]);
    }

    public function testCreateFailed()
    {
        $data = [
            "first_name" => "",
            "last_name" => "Capri",
            "email" => "omegahary88@gmail.com",
            "phone" => "089521234982"
        ];

        $this->post('/api/contacts', $data, [
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
        ])->assertStatus(400)
         ->assertJson([
            "errors" => [
                "message" => [
                    "first_name" => ["The first name field is required."]
                ]
            ]
            ]);
    }
}
