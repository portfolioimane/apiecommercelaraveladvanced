<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   
  public function index()
{
    return Product::with('category')
        ->orderBy('created_at', 'desc') // Order by creation date, newest first
        ->get();
}


    public function show($id)
    {
        return Product::findOrFail($id);
    }

   public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
    ]);

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('public/images');
        $validatedData['image'] = basename($imagePath);
    }

    $product = Product::create($validatedData);
    return response()->json($product, 201);
}

public function update(Request $request, $id)
{
    // Fetch the existing product
    $product = Product::findOrFail($id);

    // Log request data
    \Log::info('Request data:', $request->all());

    // Validate the incoming request
    $validatedData = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'description' => 'sometimes|required|string',
        'price' => 'sometimes|required|numeric',
        'stock' => 'sometimes|required|integer',
        'category_id' => 'sometimes|required|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
    ]);

    // If a new image is uploaded, handle it
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('public/images');
        \Log::info('Image path:', [$imagePath]);
        $validatedData['image'] = basename($imagePath);
    }

    // Update the product with validated data
    $product->update($validatedData);

    // Log product before and after update
    \Log::info('Product before update:', $product->toArray());
    \Log::info('Product after update:', $product->fresh()->toArray());

    // Return the updated product as a JSON response
    return response()->json($product, 200);
}



    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(null, 204);
    }

    public function fetchCategories()
    {
        return Category::all();
    }
}