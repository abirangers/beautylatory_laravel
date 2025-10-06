<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $products = Product::with('category')->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $products = Product::all();
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.products.form', compact('products', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB
            'lynk_id_link' => 'nullable|url',
        ]);

        $productData = $request->only(['name', 'category_id', 'price', 'description', 'lynk_id_link']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = FileUploadService::upload($request->file('image'), 'products');
            if ($imagePath) {
                $productData['image'] = $imagePath;
            }
        }

        Product::create($productData);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $product = cache()->remember("product_{$id}", 3600, function () use ($id) {
            return Product::with('category')->findOrFail($id);
        });
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.products.form', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB
            'lynk_id_link' => 'nullable|url',
        ]);

        $productData = $request->only(['name', 'category_id', 'price', 'description', 'lynk_id_link']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = FileUploadService::update($request->file('image'), $product->image, 'products');
            $productData['image'] = $imagePath;
        } elseif ($request->has('remove_image') && $request->remove_image) {
            // Delete existing image if requested
            FileUploadService::delete($product->image);
            $productData['image'] = null;
        }

        $product->update($productData);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        // Delete image file if exists
        FileUploadService::delete($product->image);

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function guestIndex(Request $request)
    {
        // Cache categories since they don't change often
        $categories = cache()->remember('all_categories', 3600, function () {
            return Category::orderBy('name', 'asc')->get();
        });

        // Apply filters and get products with caching
        $cacheKey = 'products_' . ($request->category ?: 'all') . '_' . $request->page;
        $products = cache()->remember($cacheKey, 600, function () use ($request) {
            $query = Product::with('category')->orderBy('created_at', 'desc');

            // Filter by category if specified
            if ($request->has('category') && !empty($request->category)) {
                $query->where('category_id', $request->category);
            }

            return $query->get();
        });

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Get all products as JSON for API calls.
     */
    public function getAll(): JsonResponse
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->get();
        return response()->json($products);
    }
}
