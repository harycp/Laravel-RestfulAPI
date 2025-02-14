<?php

namespace Tests\Feature;

use App\Models\Contact;
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

    public function testGetSuccess()
    {
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/'. $contact->id, [
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "first_name" => "Hary",
                "last_name" => "Capri",
                "email" => "omegahary88@gmail.com",
                "phone" => "08982232323"
            ]
        ]);
    }
    public function testGetFailed()
    {
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/5', [
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
        ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                "message" => "Not found contact"
            ]
        ]);
    }

    public function testUpdateSuccess()
    {
        $contact = Contact::query()->limit(1)->first();
        $data = [
            "first_name" => "Ucup",
            "last_name" => "Baba",
            "email" => "baba@gmail.com",
            "phone" => "08952321242"
        ];

        $this->patch('/api/contacts/' . $contact->id, $data, [
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
        ])->assertStatus(200);
    }
}
