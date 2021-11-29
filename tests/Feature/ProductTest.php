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
        $token = $this->loginUser()['token'];
        
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
    }

    public function test_productDelete()
    {
        $token = $this->loginUser()['token'];
        
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        $productId = Product::latest('created_at')->first()->id;
        
        $response = $this->delete("/api/products/{$productId}", [], $headers);
        $response->assertStatus(200);
    }

    public function test_updateProduct()
    {
        //login a user to get a token
        $token = $this->loginUser()['token'];
        
        $productArray = [
            'name' => 'testTest123',
            'description' => 'This is only a test.',
            'price' => '69.00'
        ];

        //build the necessary headers - this is a protected route, so we need token
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        $response = $this->post('/api/products', $productArray, $headers);
        $response->assertStatus(201);

        $productId = Product::latest('created_at')->first()->id;

        $productArray = [
            'name' => 'testTestNEW',
            'description' => 'This is only a test.',
            'price' => '69.00'
        ];

        $response = $this->put("/api/products/{$productId}", $productArray, $headers);
        $response->assertStatus(200);

        $newProduct = Product::where('id', $productId)->first();
        
        $this->assertSame('testTestNEW', $newProduct['name']);
        $response = $this->delete("/api/products/{$productId}", [], $headers);
        $response->assertStatus(200);
    }
}