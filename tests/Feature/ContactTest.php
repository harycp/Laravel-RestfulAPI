<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
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

    public function testDeleteSuccess()
    {
        $contact = Contact::query()->limit(1)->first();

        $this->delete(uri: '/api/contacts/' . $contact->id, headers: [
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
        ])->assertStatus(200);
    }

    public function testSearchFirstName()
    {
        $contacts = $this->get('/api/contacts?name=hary', [
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
        ])->assertStatus(200)->json();

        Log::info(json_encode($contacts, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($contacts['data'])); 
        self::assertEquals(11, $contacts['meta']['total']); 
    }
    public function testSearchPage()
    {
        $contacts = $this->get('/api/contacts?name=hary&page=2', [
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
        ])->assertStatus(200)->json();

        Log::info(json_encode($contacts, JSON_PRETTY_PRINT));

        self::assertEquals(1, count($contacts['data'])); 
        self::assertEquals(11, $contacts['meta']['total']); 
    }
}
