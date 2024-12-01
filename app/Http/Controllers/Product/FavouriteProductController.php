<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Favourite\AddFavouriteProductRequest;
use App\Http\Requests\Favourite\RemoveFavouriteProductRequest;
use App\Services\Favourite\FavouriteProductService;
use Exception;

class FavouriteProductController extends Controller
{
    protected FavouriteProductService $favouriteProductService;

    public function __construct(FavouriteProductService $favouriteProductService)
    {
        $this->favouriteProductService = $favouriteProductService;
    }

    public function getFavourites()
    {
        try {
            $favourites = $this->favouriteProductService->getUserFavourites(auth()->id());

            return response()->json(['data' => $favourites], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve favourites', 'message' => $e->getMessage()], 500);
        }
    }

    public function addFavourite(AddFavouriteProductRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $this->favouriteProductService->addToFavourites(auth()->id(), $validatedData['product_id']);

            return response()->json(['message' => 'Product added to favourites'], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to add favourite', 'message' => $e->getMessage()], 500);
        }
    }

    public function removeFavourite(RemoveFavouriteProductRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $this->favouriteProductService->removeFromFavourites(auth()->id(), $validatedData['product_id']);

            return response()->json(['message' => 'Product removed from favourites'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to remove favourite', 'message' => $e->getMessage()], 500);
        }
    }
}
