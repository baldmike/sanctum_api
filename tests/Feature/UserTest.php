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
    public function test_UserIndexRoute()
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

    public function test_loginUser()
    {
        $response = $this->loginUser();
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
        $response = $this->loginUser();
        $response->assertStatus(200);

        $header = [];
        $header['Accept'] = 'application/json';
        $header['Authorization'] = 'Bearer ' . $this->token;

        $currentUser = $this->get("/api/user", $header);
        $currentUser->assertStatus(200);
    }

    public function testRegisterFailsWithInvalidEmail()
    {
        $user = User::factory()->make();
        
        $data = [
            'name' => $user->name,
            'email' => "NOT A VALID EMAIL",
            'password' => $user->password,
            'password_confirmation' => $user->password,
        ];

        $response = $this->json('POST', '/api/register', $data);
        $response->assertStatus(422);
        $this->assertSame('The given data was invalid.', $response->getData()->message);
        $this->assertStringContainsString('The email must be a valid email address.', json_encode($response->getData()));
    }

    public function testRegisterFailsWithNoName()
    {
        $user = User::factory()->make();
        
        $data = [
            'name' => '',
            'email' => "testEmail@example.com",
            'password' => $user->password,
            'password_confirmation' => $user->password,
        ];

        $response = $this->json('POST', '/api/register', $data);
        $response->assertStatus(422);
        $this->assertSame('The given data was invalid.', $response->getData()->message);
        $this->assertStringContainsString('The name field is required.', json_encode($response->getData()));
    }

    public function testRegisterFailsWithNoPassword()
    {
        $user = User::factory()->make();
        
        $data = [
            'name' => 'Jerry Testerson',
            'email' => "testEmail@example.com",
            'password' => '',
            'password_confirmation' => $user->password,
        ];

        $response = $this->json('POST', '/api/register', $data);
        $response->assertStatus(422);
        $this->assertSame('The given data was invalid.', $response->getData()->message);
        $this->assertStringContainsString('The password field is required.', json_encode($response->getData()));
    }

    public function testRegisterFailsWithNoPasswordConfirmation()
    {
        $user = User::factory()->make();
        
        $data = [
            'name' => 'Jerry Testerson',
            'email' => "testEmail@example.com",
            'password' => 'password',
        ];

        $response = $this->json('POST', '/api/register', $data);
        $response->assertStatus(422);
        $this->assertSame('The given data was invalid.', $response->getData()->message);
        $this->assertStringContainsString('The password confirmation does not match.', json_encode($response->getData()));
    }
}
