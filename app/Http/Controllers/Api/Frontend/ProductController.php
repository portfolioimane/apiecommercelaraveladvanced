<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers\Api\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // Make sure you have a Product model

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::query();

        // Filter by category
        if ($request->has('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Filter by description keyword
        if ($request->has('description')) {
            $query->where('description', 'like', '%' . $request->input('description') . '%');
        }

        // Filter by price range
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->input('min_price'), $request->input('max_price')]);
        }

        // Sorting
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            $sortColumn = 'price';
            $sortDirection = 'asc';

            if ($sort === 'price_desc') {
                $sortDirection = 'desc';
            } elseif ($sort === 'name_asc') {
                $sortColumn = 'name';
            } elseif ($sort === 'name_desc') {
                $sortColumn = 'name';
                $sortDirection = 'desc';
            }

            $query->orderBy($sortColumn, $sortDirection);
        }

        $products = $query->get();

        return response()->json($products);
    }


    public function latestProducts()
    {
        // Fetch the latest products; adjust according to your requirements
        $latestProducts = Product::orderBy('created_at', 'desc')->limit(6)->get();

        return response()->json($latestProducts);
    }

    public function show($id)
    {
        // Find the product by ID or return 404 if not found
        $product = Product::findOrFail($id);

        // Fetch related products
        $relatedProducts = Product::where('category_id', $product->category_id)
                                   ->where('id', '!=', $id)
                                   ->limit(3)
                                   ->get();

        // Return a JSON response
        return response()->json([
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
