<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return view('admin.pages.products.index', compact('products'));
    }


    public function create()
    {
        return view('admin.pages.products.form');
    }


    public function store(StoreProductRequest $request)
    {
        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }


    public function show(Product $product)
    {
        //
    }


    public function edit(Product $product)
    {
        return view('admin.pages.products.form', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        
        return back();
    }
}
