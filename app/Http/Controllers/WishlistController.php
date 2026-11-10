<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class WishlistController extends Controller
{
    public function index()
    {
        $items = Cart::instance('wishlist')->content();

        return view('wishlist', compact('items'));
    }

    public function add_to_wishlist(Request $request)
    {
        $exists = Cart::instance('wishlist')->content()->where('id', $request->id)->count() > 0;

        
        if (! $exists) {
            // add($id, $name, $qty, $price)
            Cart::instance('wishlist')
                ->add($request->id, $request->name, 1, $request->price)
                ->associate(Product::class);
        }

        return redirect()->back()->with('success', 'Product added to wishlist successfully!');
    }

    public function remove_item($rowId)
    {
        Cart::instance('wishlist')->remove($rowId);

        return redirect()->back()->with('success', 'Product removed from wishlist successfully!');
    }

    public function empty_wishlist()
    {
        Cart::instance('wishlist')->destroy();

        return redirect()->back();
    }

    public function move_to_cart($rowId)
    {
        $item = Cart::instance('wishlist')->get($rowId);

        //check if the item already exists in the cart
        $existsInCart = Cart::instance('cart')->content()->where('id', $item->id)->count() > 0;

        if (! $existsInCart) {
            //add item to cart
            Cart::instance('cart')
                ->add($item->id, $item->name, 1, $item->price)
                ->associate(Product::class);
        }

        //remove item from wishlist
        Cart::instance('wishlist')->remove($rowId);

        return redirect()->back()->with('success', 'Product moved to cart successfully!');
    }
}
