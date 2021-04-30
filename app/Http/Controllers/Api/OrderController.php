<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller
{

    /**
     * All Orders.
     * GET|HEAD /carts
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $cart = auth()->user()->cart ?? null;
        if($cart){
            return new OrderResource($cart);
        }else{
            return response()->json([]);
        }
    }

    /**
     * checkout.
     *
     * @param Request $request
     *
     * @return OrderResource
     */
    public function checkout(Request $request)
    {
        $user = auth()->user();
        $cart = $user->cart;

        if ($user->balance < $user->cart->total){
            return response()->json(['error' => 'balance must be great than total'], 422);
        }

        foreach ($cart->products as $product) {
            if ($product->quantity < $product->pivot->quantity){
                return response()->json([
                    'error' => $product->name + ' not have stock'
                ], 422);
            }
        }

        $order = Order::create([
            'user_id' => $user->id,
            'total' => $cart->total,
        ]);

        foreach ($cart->products as $product) {
            $order->products()->attach($product->id, [
                'quantity' => $product->pivot->quantity,
                'price' => $product->price,
            ]);

            $product->quantity = $product->quantity - $product->pivot->quantity;
            $product->save();
        }

        $transaction = new Transaction;
        $transaction->user_id     = $user->id;
        $transaction->order_id    = $order->id;
        $transaction->type        = 'debit';
        $transaction->amount      = $cart->total;
        $transaction->description = 'order';
        $transaction->save();

        $cart->products()->detach();
        $cart->delete();

        return new OrderResource($cart);
    }


}
