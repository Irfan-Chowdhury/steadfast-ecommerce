
@extends('admin.layouts.master')

@section('title', 'Sales Form')

@section('admin_content')
<style>
    .invoice-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px 15px;
    }
    .invoice-container table {
        margin-bottom: 0;
    }
    .table-sm td, .table-sm th {
        padding: 0.5rem;
    }
    address {
        line-height: 1.5;
    }
</style>

<div class="container invoice-container">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">INVOICE</h2>
                    <small class="text-muted">#{{ $sale->invoice_number }}</small>
                </div>
                <div class="text-right">
                    <div class="font-weight-bold">Date: {{ $sale->date->format('d M Y') }}</div>
                    <div class="text-muted small">Status:
                        <span class="badge {{ $sale->due_amount > 0 ? 'badge-warning' : 'badge-success' }}">
                            {{ $sale->due_amount > 0 ? 'Partially Paid' : 'Paid' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Customer Info (if available) -->
            @if($sale->customer)
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Bill To:</h5>
                    <address class="mb-0">
                        <strong>{{ $sale->customer->name }}</strong><br>
                        {{ $sale->customer->address }}<br>
                        Phone: {{ $sale->customer->phone }}<br>
                        Email: {{ $sale->customer->email }}
                    </address>
                </div>
                <div class="col-md-6 text-right">
                    <h5>From:</h5>
                    <address class="mb-0">
                        <strong>Your Business Name</strong><br>
                        123 Business Street<br>
                        City, State 10001<br>
                        Phone: (123) 456-7890
                    </address>
                </div>
            </div>
            @endif

            <!-- Items Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="45%">Description</th>
                            <th width="15%" class="text-right">Unit Price</th>
                            <th width="15%" class="text-right">Qty</th>
                            <th width="20%" class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->saleItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td class="text-right">{{ number_format($item->unit_price, 2) }} TK</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->unit_price * $item->quantity, 2) }} TK</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <h5>Notes</h5>
                        <p class="text-muted">{{ $sale->notes ?? 'No notes available' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <th class="text-right">Subtotal:</th>
                            <td class="text-right" width="30%">{{ number_format($sale->total_amount - $sale->vat_amount + $sale->discount_amount, 2) }} TK</td>
                        </tr>
                        <tr>
                            <th class="text-right">Discount:</th>
                            <td class="text-right">{{ number_format($sale->discount_amount, 2) }} TK</td>
                        </tr>
                        <tr>
                            <th class="text-right">VAT ({{ $sale->vat_percentage }}%):</th>
                            <td class="text-right">{{ number_format($sale->vat_amount, 2) }} TK</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <th class="text-right">TOTAL:</th>
                            <td class="text-right">{{ number_format($sale->total_amount, 2) }} TK</td>
                        </tr>
                        <tr>
                            <th class="text-right">Paid Amount:</th>
                            <td class="text-right">{{ number_format($sale->paid_amount, 2) }} TK</td>
                        </tr>
                        <tr class="{{ $sale->due_amount > 0 ? 'table-warning' : 'table-success' }}">
                            <th class="text-right">Due Amount:</th>
                            <td class="text-right">{{ number_format($sale->due_amount, 2) }} TK</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="card-footer bg-white">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 small text-muted">Payment Terms: Due on receipt</p>
                </div>
                <div class="col-md-6 text-right">
                    {{-- <a href="{{ route('sales.print', $sale->id) }}" class="btn btn-sm btn-outline-secondary mr-2">
                        <i class="fas fa-print"></i> Print
                    </a> --}}
                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

