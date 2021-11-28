<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductTest extends TestCase
{
    /**
     * @return void
     */
    public function test_indexRoute()
    {
        $response = $this->get('/api/products');

        $response->assertStatus(200);
    }

    public function test_storeProduct()
    {
        //login a user to get a token
        $token = $this->loginRandomUser()['token'];
        
        //make a random product, put it in array
        $product = Product::factory()->make();
        
        $productArray = [
            'name' => $product['name'],
            'description' => $product['description'],
            'price' => $product['price']
        ];

        //build the necessary headers - this is a protected route, so we need token
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        $response = $this->post('/api/products', $productArray, $headers);
        $response->assertStatus(201);
        
        $productId = Product::latest('created_at')->first()->id;
        $this->json('delete', '/api/products/' . $productId, $headers)->assertStatus(200);
    }
}