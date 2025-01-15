<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartItemStore;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $items = $user->cartItems()->with('product')->get();

        return response()->json([
            'status' => true,
            'items' => $items,
        ]);
    }

    public function store(CartItemStore $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        $cartItem = $user->cartItems()->where('product_id', $validated['id'])->where('user_id', $user->id)->first();
        if (! $cartItem) {
            $cartItem = CartItem::create([
                'product_id' => $validated['id'],
                'user_id' => $user->id,
            ]);
        }
        $cartItem->quantity = $validated['quantity'];
        $cartItem->save();

        return response()->json([
            'status' => true,
            'cartItem' => $cartItem,
        ]);

    }

    public function delete(CartItem $cartItem)
    {
        $user = Auth::user();
        $user->cartItems()->findOrFail($cartItem->id);
        $cartItem->delete();

        return response()->json([
            'status' => true,
            'message' => 'cart item deleted successfully',
        ]);
    }
}
