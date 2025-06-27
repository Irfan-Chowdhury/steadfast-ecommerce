@extends('admin.layouts.master')

@section('title', 'Products List')

@section('admin_content')



<div class="container">
    <h2 class="mb-4">Product List</h2>
    <a href="{{ route('products.create') }}" class="btn btn-success mb-3">+ Add Product</a>

    <table class="table table-bordered text-center" id="productTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Purchase</th>
                <th>Sell</th>
                <th>Opening Stock</th>
                <th>Current Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $key => $product)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->purchase_price }}</td>
                <td>{{ $product->sell_price }}</td>
                <td>{{ $product->opening_stock }}</td>
                <td>{{ $product->current_stock }}</td>
                <td>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-info">Edit</a>

                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" id="delete-form-{{ $product->id }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $product->id }})">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('admin_scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#productTable').DataTable();
    });
</script>
@endpush
