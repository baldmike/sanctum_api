<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    /** @var string */
    protected $token = '';

    /**
     * Log in random admin user
     *
     * @return object
     */
    public function loginRandomUser()
    {
        $user = User::inRandomOrder()->first();

        $tokenData = [
            'grant_type' => 'password',
            'email' => trim($user->email),
            'password' => 'password',
            'scope' => ''
        ];

        $response = $this->json('POST', '/api/login', $tokenData);

        $json = json_decode($response->content());

        if($response->getStatusCode() == 200)
        {
            $this->token = $json->token;
        }

        return $response;
    }

    public function logoutUser()
    {
        $response = $this->json('POST', 'api/logout');

        return $response;
    }

    public function get_current_user()
    {
        return Auth::user();
    }
}
