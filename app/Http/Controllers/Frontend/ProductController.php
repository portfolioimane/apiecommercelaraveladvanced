<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Show the details of a specific product.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
 public function show($id)
{
    $product = Product::findOrFail($id);

    // Fetch related products (this is just an example; adjust the logic as needed)
    $relatedProducts = Product::where('category_id', $product->category_id)
                               ->where('id', '!=', $id)
                               ->limit(3)
                               ->get();

    return view('product.details', compact('product', 'relatedProducts'));
}

    
    /**
     * List all products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve all products
        $products = Product::all();

        // Return the view with the products data
        return view('product.index', [
            'products' => $products
        ]);
    }
}
