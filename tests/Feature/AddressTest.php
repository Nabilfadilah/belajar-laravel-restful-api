<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Contact;
use Database\Seeders\AddressSeeder;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressTest extends TestCase
{
    // create sukses
    public function testCreateSuccess()
    {
        // ambil seeder
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        // ambil data contact, dari query data ke-1
        $contact = Contact::query()->limit(1)->first();

        // kirim ke api, dengan datanya
        $this->post(
            '/api/contacts/' . $contact->id . '/addresses',
            [
                'street' => 'test',
                'city' => 'test',
                'province' => 'test',
                'country' => 'test',
                'postal_code' => '213123',
            ],
            [
                'Authorization' => 'test' // header 
            ]
        )->assertStatus(201) // response
            ->assertJson([ // hasil json
                'data' => [
                    'street' => 'test',
                    'city' => 'test',
                    'province' => 'test',
                    'country' => 'test',
                    'postal_code' => '213123',
                ]
            ]);
    }

    // create gagal
    public function testCreateFailed()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post(
            '/api/contacts/' . $contact->id . '/addresses',
            [
                'street' => 'test',
                'city' => 'test',
                'province' => 'test',
                'country' => '',
                'postal_code' => '213123',
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'country' => ['The country field is required.']
                ]
            ]);
    }

    // create contact tidak ada
    public function testCreateContactNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post(
            '/api/contacts/' . ($contact->id + 1) . '/addresses',
            [
                'street' => 'test',
                'city' => 'test',
                'province' => 'test',
                'country' => 'test',
                'postal_code' => '213123',
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['not found']
                ]
            ]);
    }

    // get sukses
    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->get('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id, [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'street' => 'test',
                    'city' => 'test',
                    'province' => 'test',
                    'country' => 'test',
                    'postal_code' => '11111'
                ]
            ]);
    }

    // get tidak ada
    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->get('/api/contacts/' . $address->contact_id . '/addresses/' . ($address->id + 1), [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['not found']
                ]
            ]);
    }

    // public function testUpdateSuccess()
    // {
    //     $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    //     $address = Address::query()->limit(1)->first();

    //     $this->put(
    //         '/api/contacts/' . $address->contact_id . '/addresses/' . $address->id,
    //         [
    //             'street' => 'update',
    //             'city' => 'update',
    //             'province' => 'update',
    //             'country' => 'update',
    //             'postal_code' => '22222'
    //         ],
    //         [
    //             'Authorization' => 'test'
    //         ]
    //     )->assertStatus(200)
    //         ->assertJson([
    //             'data' => [
    //                 'street' => 'update',
    //                 'city' => 'update',
    //                 'province' => 'update',
    //                 'country' => 'update',
    //                 'postal_code' => '22222'
    //             ]
    //         ]);
    // }

    // public function testUpdateFailed()
    // {
    //     $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    //     $address = Address::query()->limit(1)->first();

    //     $this->put(
    //         '/api/contacts/' . $address->contact_id . '/addresses/' . $address->id,
    //         [
    //             'street' => 'update',
    //             'city' => 'update',
    //             'province' => 'update',
    //             'country' => '',
    //             'postal_code' => '22222'
    //         ],
    //         [
    //             'Authorization' => 'test'
    //         ]
    //     )->assertStatus(400)
    //         ->assertJson([
    //             'errors' => [
    //                 'country' => ['The country field is required.']
    //             ]
    //         ]);
    // }

    // public function testUpdateNotFound()
    // {
    //     $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    //     $address = Address::query()->limit(1)->first();

    //     $this->put(
    //         '/api/contacts/' . $address->contact_id . '/addresses/' . ($address->id + 1),
    //         [
    //             'street' => 'update',
    //             'city' => 'update',
    //             'province' => 'update',
    //             'country' => 'update',
    //             'postal_code' => '22222'
    //         ],
    //         [
    //             'Authorization' => 'test'
    //         ]
    //     )->assertStatus(404)
    //         ->assertJson([
    //             'errors' => [
    //                 'message' => ['not found']
    //             ]
    //         ]);
    // }

    // public function testDeleteSuccess()
    // {
    //     $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    //     $address = Address::query()->limit(1)->first();

    //     $this->delete(
    //         '/api/contacts/' . $address->contact_id . '/addresses/' . $address->id,
    //         [],
    //         [
    //             'Authorization' => 'test'
    //         ]
    //     )->assertStatus(200)
    //         ->assertJson([
    //             'data' => true
    //         ]);
    // }

    // public function testDeleteNotFound()
    // {
    //     $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    //     $address = Address::query()->limit(1)->first();

    //     $this->delete(
    //         '/api/contacts/' . $address->contact_id . '/addresses/' . ($address->id + 1),
    //         [],
    //         [
    //             'Authorization' => 'test'
    //         ]
    //     )->assertStatus(404)
    //         ->assertJson([
    //             'errors' => [
    //                 'message' => ['not found']
    //             ]
    //         ]);
    // }

    // public function testListSuccess()
    // {
    //     $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    //     $contact = Contact::query()->limit(1)->first();

    //     $this->get(
    //         '/api/contacts/' . $contact->id . '/addresses',
    //         [
    //             'Authorization' => 'test'
    //         ]
    //     )->assertStatus(200)
    //         ->assertJson([
    //             'data' => [
    //                 [
    //                     'street' => 'test',
    //                     'city' => 'test',
    //                     'province' => 'test',
    //                     'country' => 'test',
    //                     'postal_code' => '11111'
    //                 ]
    //             ]
    //         ]);
    // }

    // public function testListContactNotFound()
    // {
    //     $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    //     $contact = Contact::query()->limit(1)->first();

    //     $this->get(
    //         '/api/contacts/' . ($contact->id + 1) . '/addresses',
    //         [
    //             'Authorization' => 'test'
    //         ]
    //     )->assertStatus(404)
    //         ->assertJson([
    //             'errors' => [
    //                 'message' => ['not found']
    //             ]
    //         ]);
    // }
}
