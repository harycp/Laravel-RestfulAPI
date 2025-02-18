<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddressResource;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressCreateRequest;
use App\Models\Address;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddressController extends Controller
{
    public function create(int $idContact, AddressCreateRequest $request): JsonResponse
    {
        $user = Auth::user();
        $contact = Contact::where('id', $idContact)->where('user_id', $user->id)->first();

        if(!$contact) {
            throw new HttpResponseException(response([
                'errors' => [
                    "message" => "Not found contact"
                ]
            ], 400));
        }

        $data = $request->validated();

        $address = new Address($data);
        $address->contact_id = $contact->id;
        $address->save();

        return (new AddressResource($address))->response()->setStatusCode(201);
    }
    // /contacts/{idContact}/addresses/{$idAddress}
    public function get(int $idContact, int $idAddress): AddressResource
    {
        $user = Auth::user();
        $contact = Contact::where('id', $idContact)->where('user_id', $user->id)->first();

        if(!$contact) {
            throw new HttpResponseException(response([
                'errors' => [
                    "message" => "Not found contact"
                ]
            ], 400));
        }

        $address = Address::where('id', $idAddress)->where('contact_id', $contact->id)->first();
        if(!$address) {
            throw new HttpResponseException(response([
                'errors' => [
                    "message" => "Not found address"
                ]
            ], 400));
        }

        return new AddressResource($address);
    }
}
