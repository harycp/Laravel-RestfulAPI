<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ContactResouce;
use App\Http\Requests\ContactCreateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactController extends Controller
{
    public function create(ContactCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = Auth::user();

        $contact = new Contact($data);
        $contact->user_id = $user->id;
        $contact->save();

        return (new ContactResouce($contact))->response()->setStatusCode(201);
    }

    public function get(int $id): ContactResouce
    {
        $user = Auth::user();

        $contact = Contact::where('id', $id)->where('user_id', $user->id)->first();
        if(!$contact) {
            throw new HttpResponseException(response([
                'errors' => [
                    "message" => "Not found contact"
                ]
            ], 400));
        }

        return new ContactResouce($contact);
    }
}
