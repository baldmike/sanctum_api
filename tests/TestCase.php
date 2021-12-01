<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    /** @var string */
    protected $token = '';

    /**
     * Log in random user
     *
     * @return object
     */
    public function loginUser()
    {
        $user = User::where('email', 'test@example.com')->first();

        if(!$user) {
            $data = [
                'name' => 'test user',
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ];
    
            $response = $this->json('POST', '/api/register', $data);
        }

        $loginData = [
            'grant_type' => 'password',
            'email' => 'test@example.com',
            'password' => 'password',
            'scope' => ''
        ];

        $response = $this->json('POST', '/api/login', $loginData);

        $json = json_decode($response->content());

        if($response->getStatusCode() == 200)
        {
            $this->token = $json->token;
        }

        return $response;
    }

    public function logoutUser()
    {
        // in order to logout...  we need to login and get a token
        $response = $this->loginUser();

        $header = [];
        $header['Accept'] = 'application/json';
        $header['Authorization'] = 'Bearer ' . $this->token;

        $response = $this->json('POST', 'api/logout', [], $header);

        return $response;
    }

    public function get_current_user()
    {
        return Auth::user();
    }
}
