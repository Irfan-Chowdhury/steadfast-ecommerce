<!-- resources/views/products/form.blade.php -->
@extends('admin.layouts.master')

@section('title', 'Products Form')

@section('admin_content')


<div class="container">
    <h2 class="mb-4"> <a href="{{ route('products.index') }}" class="btn btn-success mb-3">Product List</a></h2>

    <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" method="POST">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $product->name ?? '') }}">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Purchase Price</label>
            <input type="number" name="purchase_price" class="form-control" step="0.01" required value="{{ old('purchase_price', $product->purchase_price ?? '') }}">
            @error('purchase_price')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Sell Price</label>
            <input type="number" name="sell_price" class="form-control" step="0.01" required value="{{ old('sell_price', $product->sell_price ?? '') }}">
            @error('sell_price')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Opening Stock</label>
            <input type="number" name="opening_stock" class="form-control" required value="{{ old('opening_stock', $product->opening_stock ?? '') }}">
            @error('opening_stock')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Current Stock</label>
            <input type="number" name="current_stock" class="form-control" required value="{{ old('current_stock', $product->current_stock ?? '') }}">
            @error('current_stock')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($product) ? 'Update' : 'Create' }}</button>
    </form>

</div>

@endsection
