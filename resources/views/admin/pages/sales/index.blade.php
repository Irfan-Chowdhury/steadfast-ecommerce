@extends('admin.layouts.master')

@section('title', 'Sale List')

@section('admin_content')


<div class="container">
    <h2 class="mb-4">Sales List</h2>

    <a href="{{ route('sales.create') }}" class="btn btn-success mb-3">+ Create Sale</a>

    <table class="table table-bordered table-striped" id="salesTable">
        <thead>
            <tr>
                <th>#</th>
                <th># Invoice</th>
                <th>Sale Date</th>
                <th>Total</th>
                <th>Discount</th>
                <th>VAT</th>
                <th>Paid</th>
                <th>Due</th>
                <th>Items</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $key => $sale)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $sale->invoice_number }}</td>
                <td>{{ date('d M, Y',strtotime($sale->date)) }}</td>
                <td>{{ number_format($sale->total_amount, 2) }}</td>
                <td>{{ number_format($sale->discount, 2) }}</td>
                <td>{{ $sale->vat_percentage }}</td>
                <td>{{ number_format($sale->paid_amount, 2) }}</td>
                <td>{{ number_format($sale->due_amount, 2) }}</td>
                <td>{{ $sale->sale_items_count }}</td>
                <td>
                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-info">View</a>
                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure to delete?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('admin_scripts')
<!-- DataTable CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#salesTable').DataTable();
    });
</script>
@endpush
