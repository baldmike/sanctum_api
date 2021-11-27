<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testRegisterUser()
    {
        $user = User::factory()->make();
        
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password,
            'remember_token' => $user->remember_token
        ];

        $response = $this->json('POST', '/api/register', $data);

        $response->assertStatus(201)->assertJsonStructure([
            "user" => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->logoutUser();
        // delete the user we just made
        User::where('email', $user->email)->first()->delete();
    }

    public function test_loginRandomUser()
    {
        $response = $this->loginRandomUser();
        $response->assertStatus(200);

        $this->assertStringContainsString('token', $response->content());
        $this->assertStringContainsString('user', $response->content());
        $this->assertStringContainsString('message', $response->content());

        $this->logoutUser();
    }

    public function test_logout()
    {
        $response = $this->logoutUser();
        $response->assertStatus(200);
    }

    public function testCurrentUser()
    {
        $response = $this->loginRandomUser();
        $response->assertStatus(200);

        $header = [];
        $header['Accept'] = 'application/json';
        $header['Authorization'] = 'Bearer ' . $this->token;

        $currentUser = $this->get("/api/user", $header);
        $currentUser->assertStatus(200);
    }
}
