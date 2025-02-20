<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddressResource;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressCreateRequest;
use App\Http\Requests\AddressUpdateRequest;
use App\Http\Resources\AddressCollection;
use App\Models\Address;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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

    public function update(int $idContact, int $idAddress, AddressUpdateRequest $request): AddressResource
    {
        try{
            Log::info("hayy");
            $user = Auth::user();
            $contact = Contact::where('id', $idContact)->where('user_id', $user->id)->first();
            $data = $request->validated();
    
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
    
            if(isset($data['street'])) $address->street = $data['street'];
            if(isset($data['city'])) $address->city = $data['city'];
            if(isset($data['province'])) $address->province = $data['province'];
            if(isset($data['country'])) $address->country = $data['country'];
            if(isset($data['postal_code'])) $address->postal_code = $data['postal_code'];
    
            $address->save();
    
            return new AddressResource($address);
        }catch(\Exception $e){
            Log::info($e);
        }
      
    }

    public function delete(int $idContact, int $idAddress, AddressUpdateRequest $request): JsonResponse
    {
        try{
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
    
            $address->delete();
    
            return response()->json([
                "data" => true
            ], 200);
        }catch(\Exception $e){
            Log::info($e);
        }
    }

    public function getList(int $idContact): AddressCollection
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

        $address = Address::where('contact_id', $idContact)->get();

        return new AddressCollection($address);
    }
}
