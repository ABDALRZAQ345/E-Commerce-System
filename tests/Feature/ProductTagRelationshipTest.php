<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTagRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_belongs_to_many_tags()
    {
        $product = Product::factory()->create();
        $tags = Tag::factory(3)->create();

        $product->tags()->attach($tags);

        $this->assertCount(3, $product->tags);
        $this->assertTrue($product->tags->contains($tags->first()));
    }

    public function test_tag_belongs_to_many_products()
    {
        $tag = Tag::factory()->create();
        $products = Product::factory(3)->create();

        $tag->products()->attach($products);

        $this->assertCount(3, $tag->products);
        $this->assertTrue($tag->products->contains($products->first()));
    }
}
