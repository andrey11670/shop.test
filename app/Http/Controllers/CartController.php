<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function showCart()
    {
        $cartItems = CartItem::where('user_id', \Auth::id())->with('product')->get();
        return view('cart', compact('cartItems'));
    }

    public function addToCart(AddToCartRequest $request, Product $product)
    {
        $data = $request->validated();
        $user = \Auth::user();

        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem)
            $cartItem->increment('quantity', $data['quantity']);
        else {
            // Если товара нет, создаем новую запись
            CartItem::create([
                'user_id'    => $user->id,
                'product_id' => $product->id,
                'quantity'   => $data['quantity'],
                'price'      => $product->price,
            ]);
        }

        return back()->with('success', 'Товар добавлен в корзину');
    }

    public function removeFromCart($id)
    {
        $cartItem = CartItem::where('id', $id)->where('user_id', \Auth::id())->first();

        if ($cartItem)
            $cartItem->delete();

        return back()->with('success', 'Товар удален из корзины');
    }
}
