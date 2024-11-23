<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Photo;

class ProductPhotoRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_has_many_photos()
    {
        $product = Product::factory()->create();
        $photos = Photo::factory(2)->create(['object_id' => $product->id, 'object_type' => Product::class]);

        $this->assertCount(2, $product->photos);
        $this->assertTrue($product->photos->contains($photos->first()));
    }

    public function test_photo_belongs_to_polymorphic_object()
    {
        $product = Product::factory()->create();
        $photo = Photo::factory()->create(['object_id' => $product->id, 'object_type' => Product::class]);

        $this->assertTrue($photo->object->is($product));
    }
}
