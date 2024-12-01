<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Favourite\AddFavouriteStoreRequest;
use App\Http\Requests\Favourite\RemoveFavouriteStoreRequest;
use App\Models\FavouriteProduct;
use App\Models\FavouriteStore;
use App\Models\Store;
use App\Services\Favourite\FavouriteStoreService;
use Exception;
use Illuminate\Support\Facades\Auth;

class FavouriteStoreController extends Controller
{


    public function getFavourites()
    {
        try {
            $user=Auth::user();
            $favourites=$user->favouriteStores()->get();
            return response()->json([
                'status'=>true,
                'favourites' => $favourites
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve favourites', 'message' => $e->getMessage()], 500);
        }
    }

    public function addFavourite(Store $store)
    {

        try {
            $user=Auth::user();
            if(FavouriteStore::where('user_id',$user->id)->where('store_id',$store->id)->first()){
                return response()->json([
                    'status' => false,
                    'message' => 'Store is already in your favourite list'
                ]);
            }
            $user->favouriteStores()->attach($store);

            return response()->json(['message' => 'Store added to favourites'], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to add favourite', 'message' => $e->getMessage()], 500);
        }
    }

    public function removeFavourite(Store $store)
    {


            $user=Auth::user();
            $store=$user->favouriteStores()->findOrFail($store->id);
            $user->favouriteStores()->detach($store);

            return response()->json(['message' => 'Store removed from favourites'], 200);

    }
}
