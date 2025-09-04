<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressCreateRequest;
use App\Http\Requests\AddressUpdateRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\ContactResource;
use App\Models\Address;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{

    private function getContact(User $user, int $idContact): Contact // int id contact
    {
        // ambil user asli dari database pakai id/username
        $contact = Contact::where('user_id', $user->id)->where('id', $idContact)->first();

        if (!$contact) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $contact;
    }

    private function getAddress(Contact $contact, int $idAddress): Address
    {
        $address = Address::where('contact_id', $contact->id)->where('id', $idAddress)->first();
        if (!$address) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $address;
    }

    // create 
    public function create(int $idContact, AddressCreateRequest $request): JsonResponse
    {
        $user = Auth::user();
        // $contact = Contact::where('user_id', $user->id)->where('id', $idContact)->first(); // lah code ieu malah sukses
        $contact = $this->getContact($user, $idContact);

        // if (!$contact) {
        //     throw new HttpResponseException(response()->json([
        //         'errors' => [
        //             "message" => [
        //                 "not found"
        //             ]
        //         ]
        //     ])->setStatusCode(404));
        // }

        $data = $request->validated();
        $address = new Address($data);
        $address->contact_id = $contact->id;
        $address->save();

        return (new AddressResource($address))->response()->setStatusCode(201);
    }

    // get 
    // public function get(int $idContact, int $idAddress): AddressResource
    // {
    //     $user = Auth::user();
    //     $contact = Contact::where('user_id', $user->id)->where('id', $idContact)->first(); // lah code ieu malah sukses

    //     if (!$contact) {
    //         throw new HttpResponseException(response()->json([
    //             'errors' => [
    //                 "message" => [
    //                     "not found"
    //                 ]
    //             ]
    //         ])->setStatusCode(404));
    //     }

    //     $address = Address::where('contact_id', $contact->id)->where('id', $idAddress)->first(); // lah code ieu malah sukses
    //     if (!$address) {
    //         throw new HttpResponseException(response()->json([
    //             'errors' => [
    //                 "message" => [
    //                     "not found"
    //                 ]
    //             ]
    //         ])->setStatusCode(404));
    //     }

    //     return new AddressResource($address);
    // }

    // get gagal nu ieu mah bjirr
    public function get(int $idContact, int $idAddress): AddressResource
    {
        $user = Auth::user();
        $contact = $this->getContact($user, $idContact);
        $address = $this->getAddress($contact, $idAddress);

        return new AddressResource($address);
    }

    // update 
    public function update(int $idContact, int $idAddress, AddressUpdateRequest $request): AddressResource
    {
        $user = Auth::user();
        $contact = $this->getContact($user, $idContact);
        $address = $this->getAddress($contact, $idAddress);

        $data = $request->validated();
        $address->fill($data);
        $address->save();

        return new AddressResource($address);
    }

    // remove
    public function delete(int $idContact, int $idAddress): JsonResponse
    {
        $user = Auth::user();
        $contact = $this->getContact($user, $idContact);
        $address = $this->getAddress($contact, $idAddress);
        $address->delete();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }

    // list 
    public function list(int $idContact): JsonResponse
    {
        $user = Auth::user();
        $contact = $this->getContact($user, $idContact);
        $addresses = Address::where('contact_id', $contact->id)->get();
        return (AddressResource::collection($addresses))->response()->setStatusCode(200);
    }
}
