<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserStoreRelationShipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_one_store()
    {
        $user = User::factory()->create();
        $store = Store::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->store->is($store));
    }

    public function test_store_belongs_to_user()
    {
        $user = User::factory()->create();
        $store = Store::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($store->user->is($user));
    }
}
