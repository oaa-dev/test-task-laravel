<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_product_index()
    {   
        Product::factory(10)->create();

        $response = $this->get('/api/products'); 

        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Products retrieved',
            ]);
    }

    public function test_product_show()
    {
        Product::factory(1)->create();

        $response = $this->get('/api/products/1'); 

        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Product display',
            ]);
    }

    public function test_product_store()
    {
        $response = $this->post('/api/products/', [
            'name' => 'Test Product',
            'description' => 'This is a test product',
            'price' => 100,
        ]);  
        
        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Product created successfully',
            ]);
    }

    public function test_product_update()
    {
        Product::factory(1)->create();

        $response = $this->put('/api/products/1', [
            'name' => 'Test Product (updated data)',
            'description' => 'This is a test product (updated data)',
            'price' => 100,
        ]);  
        
        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Product updated successfully',
            ]);
    }

    public function test_product_delete()
    {
        Product::factory(1)->create();

        $response = $this->delete('/api/products/1');  
        
        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Product deleted successfully',
            ]);
    }

    public function test_product_review_store()
    {
        Product::factory(1)->create();

        $response = $this->post('/api/products/1/reviews', [
            'user_name' => 'admin_user',
            'rating' => 1,
            'comment' => "this is a comment in unit test",
        ]); 
        
        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Product review created successfully',
            ]);
    }
}
