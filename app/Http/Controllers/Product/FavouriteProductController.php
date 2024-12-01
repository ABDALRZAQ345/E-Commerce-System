<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\FavouriteProduct;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Auth;

class FavouriteProductController extends Controller
{


    public function getFavourites()
    {
        try {
            $user=Auth::user();
            $favourites=$user->favouriteProducts()->get();

            return response()->json([
                'status' => true,
                'favourites' => $favourites
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve favourites', 'message' => $e->getMessage()], 500);
        }
    }

    public function addFavourite(Product $product)
    {

        try {
            $user=Auth::user();
            if(FavouriteProduct::where('user_id',$user->id)->where('product_id',$product->id)->first()){
                return response()->json([
                    'status' => false,
                    'message' => 'Product is already in your favourite list'
                ]);
            }
            $user->favouriteProducts()->attach($product);

            return response()->json([
                'message' => 'Product added to favourites'
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to add favourite', 'message' => $e->getMessage()], 500);
        }
    }

    public function removeFavourite(Product $product)
    {


            $user=Auth::user();
            $product=$user->favouriteProducts()->findOrFail($product->id);
            $user->favouriteProducts()->detach($product);

            return response()->json(['message' => 'Product removed from favourites'], 200);

    }
}
