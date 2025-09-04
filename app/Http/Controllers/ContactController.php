<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    // create
    public function create(ContactCreateRequest $request): JsonResponse
    {
        // validasi request data
        $data = $request->validated();
        $user = Auth::user(); // ambil data user yang sedang login

        // contact ambil dari data
        $contact = new Contact($data);
        $contact->user_id = $user->id; // user id ambil dari user id
        $contact->save(); // save

        // retrun contact response
        return (new ContactResource($contact))->response()->setStatusCode(201);
    }

    // get 
    public function get(int $id): ContactResource
    {
        // ambil data user yang sedang login
        $user = Auth::user();

        // ambil contact asli dari database pakai id user id
        $contact = Contact::where('id', $id)->where('user_id', $user->id)->first();
        // jika bukan id contact
        if (!$contact) {
            // kasih erorr response
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        // retrun contact response
        return new ContactResource($contact);
    }

    // update 
    public function update(int $id, ContactUpdateRequest $request): ContactResource // ambil id nya, parameter request ContactUpdateRequest 
    {
        // user yg sedang login
        $user = Auth::user();

        // ambil contact asli dari database pakai id user id
        $contact = Contact::where('id', $id)->where('user_id', $user->id)->first();
        if (!$contact) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        $data = $request->validated(); // dalidasi
        $contact->fill($data);
        $contact->save(); // simpan

        return new ContactResource($contact);
    }

    // delete
    public function delete(int $id): JsonResponse // balikan jsonresponse
    {
        // ambil yg login
        $user = Auth::user();

        // ambil contact asli dari database pakai id user id
        $contact = Contact::where('id', $id)->where('user_id', $user->id)->first();
        if (!$contact) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        // delete contact datanya
        $contact->delete();
        return response()->json([
            'data' => true // data true
        ])->setStatusCode(200); // response 
    }

    // public function search(Request $request): ContactCollection
    // {
    //     $user = Auth::user();
    //     $page = $request->input('page', 1);
    //     $size = $request->input('size', 10);

    //     $contacts = Contact::query()->where('user_id', $user->id);

    //     $contacts = $contacts->where(function (Builder $builder) use ($request) {
    //         $name = $request->input('name');
    //         if ($name) {
    //             $builder->where(function (Builder $builder) use ($name) {
    //                 $builder->orWhere('first_name', 'like', '%' . $name . '%');
    //                 $builder->orWhere('last_name', 'like', '%' . $name . '%');
    //             });
    //         }

    //         $email = $request->input('email');
    //         if ($email) {
    //             $builder->where('email', 'like', '%' . $email . '%');
    //         }

    //         $phone = $request->input('phone');
    //         if ($phone) {
    //             $builder->where('phone', 'like', '%' . $phone . '%');
    //         }
    //     });

    //     $contacts = $contacts->paginate(perPage: $size, page: $page);

    //     return new ContactCollection($contacts);
    // }
}
