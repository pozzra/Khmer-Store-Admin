<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return Inertia::render('Products/Index', [
            'productss' => $products
        ]);
    }

    public function create()
    {
        return Inertia::render('Products/create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mage' => ['required', 'image', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'dsc' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'integer'], // Add stock validation
        ]);

        if ($request->hasFile('mage')) {
            $path = $request->file('mage')->store('products', 'public');
            $validated['mage'] = $path;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return Inertia::render('Products/edit', [
            'product' => $product
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'mage' => ['nullable', 'image', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'dsc' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'integer'], // Add stock validation
        ]);

        if ($request->hasFile('mage')) {
            $path = $request->file('mage')->store('products', 'public');
            $validated['mage'] = $path;
        } else {
            // Keep the old image if no new file is uploaded
            $validated['mage'] = $product->mage;
        }

        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
    public function apiIndex()
{
    return response()->json(Product::all());
}

    public function stockIndex()
    {
        $products = Product::all();
        return Inertia::render('Stock/Index', [
            'productss' => $products
        ]);
    }

    public function stockCreate()
    {
        return Inertia::render('Stock/create');
    }

    public function stockEdit($id)
    {
        $product = Product::findOrFail($id);
        return Inertia::render('Stock/edit', [
            'product' => $product
        ]);
    }
}