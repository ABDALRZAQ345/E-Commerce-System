<?php

namespace App\Services\Favourite;

use App\Models\FavouriteStore;
use Exception;

class FavouriteStoreService
{
    public function getUserFavourites(int $userId)
    {
        try {
            return FavouriteStore::where('user_id', $userId)
                ->with('store')
                ->get();
        } catch (Exception $e) {
            throw new Exception('Error retrieving user favourites: ' . $e->getMessage());
        }
    }

    public function addToFavourites($userId, $storeId)
    {
        try {
            $exists = FavouriteStore::where('user_id', $userId)
                ->where('store_id', $storeId)
                ->exists();

            if (!$exists) {
                FavouriteStore::create([
                    'user_id' => $userId,
                    'store_id' => $storeId,
                ]);
            }
        } catch (Exception $e) {
            throw new Exception('Error adding to favourites: ' . $e->getMessage());
        }
    }

    public function removeFromFavourites(int $userId, int $storeId)
    {
        try {
            FavouriteStore::where('user_id', $userId)
                ->where('store_id', $storeId)
                ->delete();
        } catch (Exception $e) {
            throw new Exception('Error removing from favourites: ' . $e->getMessage());
        }
    }
}
