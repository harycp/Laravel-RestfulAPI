<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Address;
use App\Models\Contact;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressTest extends TestCase
{
    public function testCreateAddress()
    {
        $contact = Contact::query()->limit(1)->first();

        $data = [
            "street" => "Jl. Semangka 2",
            "city" => "Bekasi",
            "province" => "Jawa Barat",
            "country" => "Indonesia",
            "postal_code" => "17520"
        ];

        $this->post('/api/contacts/' . $contact->id . '/addresses', $data, [
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
        ])->assertStatus(201);
    }

    public function testGetAddressSuccess()
    {
        $contact = Contact::query()->limit(1)->first();
        $address = Address::where('contact_id', $contact->id)->first();

        // Log::info(json_encode($address));

        $this->get('/api/contacts/' . $contact->id . '/addresses/' . $address->id,  headers: [
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                'id' => 1,
                'street' => 'Jl. Semangka 1',
                'city' => 'Bekasi',
                'province' => 'Jawa Barat',
                'country' => 'Indonesia',
                'postal_code' => '17520',
            ]
        ]);
    }

    public function testGetAddressFailed()
    {
        $contact = Contact::query()->limit(1)->first();
        $address = Address::where('contact_id', $contact->id)->first();

        // Log::info(json_encode($address));

        $this->get('/api/contacts/' . $contact->id . '/addresses/0912',  headers: [
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
        ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                "message" => "Not found address"
            ]
        ]);
    }

    public function testUpdateAddressSuccess()
    {
        $contact = Contact::query()->limit(1)->first();
        $address = Address::where('contact_id', $contact->id)->first();

        Log::info("User: " . json_encode(Auth::user()));


        Log::info(json_encode($address));
        $data = [
            "street" => "Jl Jl Jl Mantap",
            "city" => "Piyungan",
            "province" => "Jawa Barat",
            "country" => "Indonesia",
            "postal_code" => "17220"
        ];

        $response = $this->put('/api/contacts/' . $contact->id . '/addresses/' . $address->id,  data: $data, headers:[
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
        ])->assertStatus(200);

        Log::info(json_encode($response));
        self::assertNotEquals($address, $response);
    }

    public function testDeleteAddressSuccess()
    {
        $contact = Contact::query()->limit(1)->first();
        $address = Address::where('contact_id', $contact->id)->first();
        Log::info(json_encode($address));
        
        Log::info("User: " . json_encode(Auth::user()));
        
        $this->delete('/api/contacts/' . $contact->id . '/addresses/' . $address->id,  headers:[
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
            ])->assertStatus(200)
        ->assertJson([
            "data" => true
        ]);
    }
    
    public function testGetListAddressSuccess()
    {
        $contact = Contact::query()->limit(1)->first();

        $response = $this->get('/api/contacts/' . $contact->id . '/addresses',  headers:[
            "Authorization" => "ff6c8f47-7a0c-4abd-bd89-e19c6de3ce76"
            ])->assertStatus(200);

        self::assertNotNull($response);
    }
}
