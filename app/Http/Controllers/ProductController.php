<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderByDesc('id')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        DB::transaction(function() use ($request){
            $validated = $request->validated();

            // Handle icon file upload
            if($request->hasFile('icon')){
                $iconPath = $request->file('icon')->store('icon', 'public');
                $validated['icon'] = $iconPath;
            }

            // Handle thumbnail file upload
            if($request->hasFile('thumbnail')){
                $thumbnailPath = $request->file('thumbnail')->store('thumbnail', 'public');
                $validated['thumbnail'] = $thumbnailPath;
            }

            // Create the new product
            Product::create($validated);
        });

        // Redirect to the product index page
        return redirect()->route('admin.products.index')->with('success', 'Product successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Display the product details
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::transaction(function() use ($request, $product){
            $validated = $request->validated();

            // Handle icon file upload if new icon is uploaded
            if($request->hasFile('icon')){
                // Delete old icon if exists
                if ($product->icon) {
                    Storage::disk('public')->delete($product->icon);
                }
                $iconPath = $request->file('icon')->store('icon', 'public');
                $validated['icon'] = $iconPath;
            } else {
                // Use old icon if no new file is uploaded
                $validated['icon'] = $product->icon;
            }

            // Handle thumbnail file upload if new thumbnail is uploaded
            if($request->hasFile('thumbnail')){
                // Delete old thumbnail if exists
                if ($product->thumbnail) {
                    Storage::disk('public')->delete($product->thumbnail);
                }
                $thumbnailPath = $request->file('thumbnail')->store('thumbnail', 'public');
                $validated['thumbnail'] = $thumbnailPath;
            } else {
                // Use old thumbnail if no new file is uploaded
                $validated['thumbnail'] = $product->thumbnail;
            }

            // Update the product with validated data
            $product->update($validated);
        });

        // Redirect to the product index page after update
        return redirect()->route('admin.products.index')->with('success', 'Product successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        DB::transaction(function() use ($product){
            // Delete the product files if they exist
            if ($product->icon) {
                Storage::disk('public')->delete($product->icon);
            }
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }

            // Delete the product from the database
            $product->delete();
        });

        return redirect()->route('admin.products.index')->with('success', 'Product successfully deleted.');
    }
}
