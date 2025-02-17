<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Contact;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressTest extends TestCase
{
    public function testCreateAddress()
    {
        $contact = Contact::query()->limit(1)->first();

        $data = [
            "street" => "Jl. Semangka 1",
            "city" => "Bekasi",
            "province" => "Jawa Barat",
            "country" => "Indonesia",
            "postal_code" => "17520"
        ];

        $this->post('/api/contacts/' . $contact->id . '/addresses', $data, [
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
        ])->assertStatus(201);
    }
}
