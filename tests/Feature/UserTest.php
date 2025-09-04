<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    // register berhasil
    public function testRegisterSuccess()
    {
        // kirim ke api, dengan data valuenya
        $this->post('/api/users', [
            'username' => 'nabil',
            'password' => 'rahasia',
            'name' => 'M Nabil Fadilah'
        ])->assertStatus(201) // response
            ->assertJson([ // kirim dalam json
                "data" => [
                    'username' => 'nabil',
                    'name' => 'M Nabil Fadilah'
                ]
            ]);
    }

    // register gagal
    public function testRegisterFailed()
    {
        // kirim ke api, dengan data valuenya
        $this->post('/api/users', [
            'username' => '',
            'password' => '',
            'name' => ''
        ])->assertStatus(400) // response
            ->assertJson([ // kirim dalam json
                "errors" => [
                    'username' => [
                        "The username field is required."
                    ],
                    'password' => [
                        "The password field is required."
                    ],
                    'name' => [
                        "The name field is required."
                    ]
                ]
            ]);
    }

    // user yang sudah ada
    public function testRegisterUsernameAlreadyExists()
    {
        // funggil function testRegisterSuccess
        $this->testRegisterSuccess();

        // kirim ke api, dengan data valuenya
        $this->post('/api/users', [
            'username' => 'nabil',
            'password' => 'rahasia',
            'name' => 'M Nabil Fadilah'
        ])->assertStatus(400) // response
            ->assertJson([ // kirim dalam json
                "errors" => [
                    'username' => [
                        "username already registered"
                    ]
                ]
            ]);
    }

    // public function testLoginSuccess()
    // {
    //     $this->seed([UserSeeder::class]);
    //     $this->post('/api/users/login', [
    //         'username' => 'test',
    //         'password' => 'test'
    //     ])->assertStatus(200)
    //         ->assertJson([
    //             'data' => [
    //                 'username' => 'test',
    //                 'name' => 'test'
    //             ]
    //         ]);

    //     $user = User::where('username', 'test')->first();
    //     self::assertNotNull($user->token);
    // }

    // public function testLoginFailedUsernameNotFound()
    // {
    //     $this->post('/api/users/login', [
    //         'username' => 'test',
    //         'password' => 'test'
    //     ])->assertStatus(401)
    //         ->assertJson([
    //             'errors' => [
    //                 "message" => [
    //                     "username or password wrong"
    //                 ]
    //             ]
    //         ]);
    // }

    // public function testLoginFailedPasswordWrong()
    // {
    //     $this->seed([UserSeeder::class]);
    //     $this->post('/api/users/login', [
    //         'username' => 'test',
    //         'password' => 'salah'
    //     ])->assertStatus(401)
    //         ->assertJson([
    //             'errors' => [
    //                 "message" => [
    //                     "username or password wrong"
    //                 ]
    //             ]
    //         ]);
    // }

    // public function testGetSuccess()
    // {
    //     $this->seed([UserSeeder::class]);

    //     $this->get('/api/users/current', [
    //         'Authorization' => 'test'
    //     ])->assertStatus(200)
    //         ->assertJson([
    //             'data' => [
    //                 'username' => 'test',
    //                 'name' => 'test'
    //             ]
    //         ]);
    // }

    // public function testGetUnauthorized()
    // {
    //     $this->seed([UserSeeder::class]);

    //     $this->get('/api/users/current')
    //         ->assertStatus(401)
    //         ->assertJson([
    //             'errors' => [
    //                 'message' => [
    //                     'unauthorized'
    //                 ]
    //             ]
    //         ]);
    // }

    // public function testGetInvalidToken()
    // {
    //     $this->seed([UserSeeder::class]);

    //     $this->get('/api/users/current', [
    //         'Authorization' => 'salah'
    //     ])->assertStatus(401)
    //         ->assertJson([
    //             'errors' => [
    //                 'message' => [
    //                     'unauthorized'
    //                 ]
    //             ]
    //         ]);
    // }

    // public function testUpdatePasswordSuccess()
    // {
    //     $this->seed([UserSeeder::class]);
    //     $oldUser = User::where('username', 'test')->first();

    //     $this->patch(
    //         '/api/users/current',
    //         [
    //             'password' => 'baru'
    //         ],
    //         [
    //             'Authorization' => 'test'
    //         ]
    //     )->assertStatus(200)
    //         ->assertJson([
    //             'data' => [
    //                 'username' => 'test',
    //                 'name' => 'test'
    //             ]
    //         ]);

    //     $newUser = User::where('username', 'test')->first();
    //     self::assertNotEquals($oldUser->password, $newUser->password);
    // }

    // public function testUpdateNameSuccess()
    // {
    //     $this->seed([UserSeeder::class]);
    //     $oldUser = User::where('username', 'test')->first();

    //     $this->patch(
    //         '/api/users/current',
    //         [
    //             'name' => 'Eko'
    //         ],
    //         [
    //             'Authorization' => 'test'
    //         ]
    //     )->assertStatus(200)
    //         ->assertJson([
    //             'data' => [
    //                 'username' => 'test',
    //                 'name' => 'Eko'
    //             ]
    //         ]);

    //     $newUser = User::where('username', 'test')->first();
    //     self::assertNotEquals($oldUser->name, $newUser->name);
    // }

    // public function testUpdateFailed()
    // {
    //     $this->seed([UserSeeder::class]);

    //     $this->patch(
    //         '/api/users/current',
    //         [
    //             'name' => 'EkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEko'
    //         ],
    //         [
    //             'Authorization' => 'test'
    //         ]
    //     )->assertStatus(400)
    //         ->assertJson([
    //             'errors' => [
    //                 'name' => [
    //                     "The name field must not be greater than 100 characters."
    //                 ]
    //             ]
    //         ]);
    // }

    // public function testLogoutSuccess()
    // {
    //     $this->seed([UserSeeder::class]);

    //     $this->delete(uri: '/api/users/logout', headers: [
    //         'Authorization' => 'test'
    //     ])->assertStatus(200)
    //         ->assertJson([
    //             "data" => true
    //         ]);

    //     $user = User::where('username', 'test')->first();
    //     self::assertNull($user->token);
    // }

    // public function testLogoutFailed()
    // {
    //     $this->seed([UserSeeder::class]);

    //     $this->delete(uri: '/api/users/logout', headers: [
    //         'Authorization' => 'salah'
    //     ])->assertStatus(401)
    //         ->assertJson([
    //             "errors" => [
    //                 "message" => [
    //                     "unauthorized"
    //                 ]
    //             ]
    //         ]);
    // }
}
