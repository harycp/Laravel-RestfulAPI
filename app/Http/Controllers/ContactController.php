<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ContactResouce;
use App\Http\Resources\ContactCollection;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\ContactUpdateRequest;
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

    public function update(ContactUpdateRequest $request, int $id): ContactResouce
    {
        $user = Auth::user();
        $contact = Contact::where('id', $id)->where('user_id', $user->id)->first();
        $data = $request->validated();

        if(!$contact) {
            throw new HttpResponseException(response([
                'errors' => [
                    "message" => "Not found contact"
                ]
            ], 400));
        }

        if(isset($data['first_name'])) $contact->first_name = $data['first_name'];
        if(isset($data['last_name'])) $contact->last_name = $data['last_name'];
        if(isset($data['email'])) $contact->email = $data['email'];
        if(isset($data['phone'])) $contact->phone = $data['phone'];

        $contact->save();

        return new ContactResouce($contact);

    }

    public function remove(int $id): JsonResponse
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

        $contact->delete();

        return response()->json([
            "data" => true
        ], 200);
    }
    public function search(Request $request): ContactCollection
    {
        $user = Auth::user();
        
        $size = $request->input('size', 10);

        $contacts = Contact::where('user_id', $user->id);

        $contacts = $contacts->where(function(Builder $builder) use ($request){
            $name = $request->input('name');
            if($name) {
                $builder->where(function(Builder $builder) use ($name) {
                    $builder->orWhere('first_name', 'like', '%'. $name . '%');
                    $builder->orWhere('last_name', 'like', '%' . $name . '%');
                });
            }

            $email = $request->input('email');
            if($email) {
                $builder->where(function(Builder $builder) use ($email) {
                    $builder->orWhere('email', 'like', '%' . $email . '%');
                });
            }

            $phone = $request->input('phone');
            if($phone) {
                $builder->where(function(Builder $builder) use ($phone) {
                    $builder->orWhere('phone', 'like', '%' . $phone . '%');
                });
            }
        })->paginate($size);

        return new ContactCollection($contacts);

    }
}
