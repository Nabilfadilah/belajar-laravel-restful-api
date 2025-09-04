<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
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

    // login sukses
    public function testLoginSuccess()
    {
        // ambil seeder
        $this->seed([UserSeeder::class]);

        // kirim ke api, dengan data valuenya
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'test'
        ])->assertStatus(200) // response
            ->assertJson([ // kirim dalam json
                'data' => [
                    'username' => 'test',
                    'name' => 'test'
                ]
            ]);

        // ambil database user dari username yang test, yang pertama
        $user = User::where('username', 'test')->first();
        self::assertNotNull($user->token); // jangan lupa berikan token yg gak null
    }

    // login gagal
    public function testLoginFailedUsernameNotFound()
    {
        // kirim ke api, dengan data valuenya
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'test'
        ])->assertStatus(401) // response
            ->assertJson([ // harus json
                'errors' => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ]);
    }

    // login yang password salah
    public function testLoginFailedPasswordWrong()
    {
        // ambil seeder
        $this->seed([UserSeeder::class]);

        // kirim ke api, dengan data valuenya
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'salah'
        ])->assertStatus(401) // response
            ->assertJson([ // dalam json
                'errors' => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ]);
    }

    // get sukses
    public function testGetSuccess()
    {
        // ambil seeder
        $this->seed([UserSeeder::class]);

        // kirim ke api, dengan data valuenya
        $this->get('/api/users/current', [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'test'
                ]
            ]);
    }

    // get gagal
    public function testGetUnauthorized()
    {
        // ambil seeder 
        $this->seed([UserSeeder::class]);

        // kirim ke api, dengan data valuenya
        $this->get('/api/users/current')
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);
    }

    // get token gagal
    public function testGetInvalidToken()
    {
        // ambil seeder
        $this->seed([UserSeeder::class]);

        // kirim ke api, dengan data valuenya
        $this->get('/api/users/current', [
            'Authorization' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);
    }

    // update password sukses
    public function testUpdatePasswordSuccess()
    {
        // ambil seeder
        $this->seed([UserSeeder::class]);
        // ambil data old, dari username value 'test'
        $oldUser = User::where('username', 'test')->first();

        // kirim ke api, dengan data valuenya
        $this->patch(
            '/api/users/current',
            [
                'password' => 'baru'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'test'
                ]
            ]);

        //ambil data user yang baru, dari username test
        $newUser = User::where('username', 'test')->first();
        // passwordnya gak boleh sama lagi!!
        self::assertNotEquals($oldUser->password, $newUser->password);
    }

    // update name sukses
    public function testUpdateNameSuccess()
    {
        // ambil seeder
        $this->seed([UserSeeder::class]);
        // ambil data old, dari username value 'test'
        $oldUser = User::where('username', 'test')->first();

        // kirim ke api, dengan data valuenya
        $this->patch(
            '/api/users/current',
            [
                'name' => 'Eko'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'Eko'
                ]
            ]);

        //ambil data user yang baru, dari username test
        $newUser = User::where('username', 'test')->first();
        // name gak boleh sama lagi!!
        self::assertNotEquals($oldUser->name, $newUser->name);
    }

    // update gagal
    public function testUpdateFailed()
    {
        // ambil seeder
        $this->seed([UserSeeder::class]);

        // kirim ke api, dengan data valuenya
        $this->patch(
            '/api/users/current',
            [
                'name' => 'EkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEko'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        "The name field must not be greater than 100 characters."
                    ]
                ]
            ]);
    }

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
