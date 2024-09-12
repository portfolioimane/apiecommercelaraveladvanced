<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $latestProducts = Product::latest()->take(6)->get(); 
      //  dd($latestProducts);// Fetch the latest 6 products
        return view('home', compact('latestProducts'));
    }
}
