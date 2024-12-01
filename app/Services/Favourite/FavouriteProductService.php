<?php

namespace App\Services\Favourite;

use App\Models\FavouriteProduct;
use Exception;

class FavouriteProductService
{
    public function getUserFavourites(int $userId)
    {
        try {
            return FavouriteProduct::where('user_id', $userId)
                ->with('product')
                ->get();
        } catch (Exception $e) {
            throw new Exception('Error retrieving user favourites: ' . $e->getMessage());
        }
    }

    public function addToFavourites($userId, $productId)
    {
        try {
            $exists = FavouriteProduct::where('user_id', $userId)
                ->where('product_id', $productId)
                ->exists();

            if (!$exists) {
                FavouriteProduct::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                ]);
            }
        } catch (Exception $e) {
            throw new Exception('Error adding to favourites: ' . $e->getMessage());
        }
    }

    public function removeFromFavourites(int $userId, int $productId)
    {
        try {
            FavouriteProduct::where('user_id', $userId)
                ->where('product_id', $productId)
                ->delete();
        } catch (Exception $e) {
            throw new Exception('Error removing from favourites: ' . $e->getMessage());
        }
    }
}
