<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreProductRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_has_many_products()
    {
        $store = Store::factory()->create();
        $category = Category::create([
            'name' => 'test',
        ]);
        $products = Product::factory(3)->create(['store_id' => $store->id, 'category_id' => $category->id]);

        $this->assertCount(3, $store->products);
        $this->assertTrue($store->products->contains($products->first()));
    }

    public function test_product_belongs_to_store()
    {
        $store = Store::factory()->create();
        $category = Category::create([
            'name' => 'test',
        ]);
        $product = Product::factory()->create(['store_id' => $store->id, 'category_id' => $category->id]);

        $this->assertTrue($product->store->is($store));
    }
}
