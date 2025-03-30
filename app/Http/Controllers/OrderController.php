<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $cartItems = json_decode($request->items, true);

        if (!$cartItems || count($cartItems) === 0)
            return redirect()->back()->with('error', 'Корзина пуста');

        $authUserId = \Auth::id();
        $statusPaid = true;

        DB::beginTransaction();
        try {
            $order = Order::create([
                'number' => mt_rand(100000000000, 999999999999),
                'user_id' => $authUserId,
                'total_price' => collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']),
                'payment_status' => $statusPaid,
                'date' => Carbon::now(),
            ]);

            foreach ($cartItems as $id => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            CartItem::where('user_id', $authUserId)->delete();

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Заказ оформлен');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ошибка при оформлении заказа: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $orders = Order::where('user_id', \Auth::id())->with('items.product')->latest()->get();
        return view('orders', compact('orders'));
    }

    public function removeFromOrder(){
        Order::where('user_id', \Auth::id())->delete();
        return redirect()->route('index');
    }
}
