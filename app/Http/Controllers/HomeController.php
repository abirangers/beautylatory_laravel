<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Slider;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index(): View
    {
        $sliders = cache()->remember('home_sliders', 3600, function () {
            return Slider::orderBy('order', 'asc')->get();
        });

        $products = cache()->remember('home_products', 3600, function () {
            return Product::with('category')->orderBy('created_at', 'desc')->limit(8)->get();
        });

        return view('home.index', compact('sliders', 'products'));
    }
}
