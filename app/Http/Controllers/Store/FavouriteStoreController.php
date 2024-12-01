<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Favourite\AddFavouriteStoreRequest;
use App\Http\Requests\Favourite\RemoveFavouriteStoreRequest;
use App\Services\Favourite\FavouriteStoreService;
use Exception;

class FavouriteStoreController extends Controller
{
    protected FavouriteStoreService $favouriteStoreService;

    public function __construct(FavouriteStoreService $favouriteStoreService)
    {
        $this->favouriteStoreService = $favouriteStoreService;
    }

    public function getFavourites()
    {
        try {
            $favourites = $this->favouriteStoreService->getUserFavourites(auth()->id());

            return response()->json(['data' => $favourites], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve favourites', 'message' => $e->getMessage()], 500);
        }
    }

    public function addFavourite(AddFavouriteStoreRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $this->favouriteStoreService->addToFavourites(auth()->id(), $validatedData['store_id']);

            return response()->json(['message' => 'Store added to favourites'], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to add favourite', 'message' => $e->getMessage()], 500);
        }
    }

    public function removeFavourite(RemoveFavouriteStoreRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $this->favouriteStoreService->removeFromFavourites(auth()->id(), $validatedData['store_id']);

            return response()->json(['message' => 'Store removed from favourites'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to remove favourite', 'message' => $e->getMessage()], 500);
        }
    }
}
