<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
        public function index(){
            $products = Product::all();
            $cart = CartItem::where('user_id', \Auth::id())->get();
            return view('main', ['products' => $products, 'cart' => $cart]);
        }
}
