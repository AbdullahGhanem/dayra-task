<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class CartController extends Controller
{
    /**
     * Display a listing of the Cart.
     * GET|HEAD /carts
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $cart = auth()->user()->cart ?? null;
        if($cart){
            return new CartResource($cart);
        }else{
            return response()->json([]);
        }
    }

    /**
     * Store a newly created Cart in storage.
     *
     * @param Request $request
     *
     * @return CartResource
     */
    public function store(Request $request)
    {
        $product = Product::find($request->product_id);
        $user = auth()->user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        if($cart->products()->where('product_id', $product->id)->count() == 0){
            $cart->products()->attach($product->id, [
                'quantity' => 1
            ]);

        }else{
            $item = DB::table('cart_product')->where([
                'product_id' => $product->id,
                'cart_id' => $cart->id
            ])->first();
            
            DB::table('cart_product')->where([
                'product_id' => $product->id,
                'cart_id' => $cart->id
            ])->update([
                'quantity' => $item->quantity +1
            ]);
        }
        $cart->updated_at = Carbon::now();
        $cart->save();

        return new CartResource($cart);

    }


}
