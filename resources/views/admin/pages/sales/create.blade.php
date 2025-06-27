
@extends('admin.layouts.master')

@section('title', 'Sales Form')

@section('admin_content')


<div class="container">
    <h2 class="mb-4"> <a href="{{ route('sales.index') }}" class="btn btn-success mb-3">Sale List</a></h2>

    <form method="POST" action="{{ route('sales.store') }}">
        @csrf

        <div class="form-group">
            <label>Sale Date</label>
            <input type="date" name="sale_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            @error('sale_date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <hr>

        <h5>Products</h5>
        <table class="table table-bordered" id="product_table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th><button type="button" class="btn btn-sm btn-success" id="addRow">+</button></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="products[0][product_id]" class="form-control product-dropdown">

                            <option>--- Select ---</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->sell_price }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="products[0][quantity]" class="form-control qty" required></td>
                    {{-- <td><input type="number" step="0.01" name="products[0][unit_price]" class="form-control price" required></td> --}}
                    <td><input type="number" step="0.01" name="products[0][unit_price]" class="form-control price" readonly></td>
                    <td><input type="text" class="form-control total" readonly></td>
                    <td><button type="button" class="btn btn-sm btn-danger removeRow">X</button></td>
                </tr>
            </tbody>
        </table>

        <div class="form-group">
            <label>Discount (TK)</label>
            <input type="text" name="discount" class="form-control" value="0">
            @error('discount')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>VAT (%)</label>
            <input type="number" name="vat_percent" class="form-control" value="0">
            @error('vat_percent')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Paid Amount</label>
            <input type="text" name="paid_amount" class="form-control">
            @error('paid_amount')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <input type="hidden" name="vat_amount">
        <input type="hidden" name="total_amount">

        <div class="form-group">
            <label>Due Amount</label>
            <input type="number" name="due_amount" class="form-control" readonly>
        </div>

        {{-- <div class="form-group">
            <label>Payment Type</label>
            <select name="payment_type" class="form-control" required>
                <option value="Cash">Cash</option>
                <option value="Due">Due</option>
            </select>
        </div> --}}

        <button class="btn btn-primary">Submit</button>
    </form>
<div>

@endsection


@push('admin_scripts')

<script>
    $(document).on('change', '.product-dropdown', function () {
        var selectedOption = $(this).find(':selected');
        var price = selectedOption.data('price');
        var row = $(this).closest('tr');
        row.find('.price').val(price);

        // Get quantity
        var qty = parseFloat(row.find('.qty').val()) || 1;

        // Calculate and set total
        var total = price * qty;
        row.find('.total').val(total.toFixed(2));

        calculateSummary();
    });
</script>

<script>
    let row = 1;
    $('#addRow').click(function () {
        const allProducts = @json($products);
        let html = `
        <tr>
            <td>
                <select name="products[${row}][product_id]" class="form-control">
                    @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="products[${row}][quantity]" class="form-control qty"></td>
            <td><input type="number" step="0.01" name="products[${row}][unit_price]" class="form-control price"></td>
            <td><input type="text" class="form-control total" readonly></td>
            <td><button type="button" class="btn btn-sm btn-danger removeRow">X</button></td>
        </tr>`;
        $('#product_table tbody').append(html);
        row++;
    });

    $(document).on('click', '.removeRow', function () {
        $(this).closest('tr').remove();
    });

    $(document).on('input', '.qty, .price', function () {
        let tr = $(this).closest('tr');
        let qty = parseFloat(tr.find('.qty').val()) || 0;
        let price = parseFloat(tr.find('.price').val()) || 0;
        tr.find('.total').val((qty * price).toFixed(2));
    });
</script>


{{-- <script>
    let row = 1;
    const allProducts = @json($products);

    // 🔁 Function: Get selected product IDs
    function getSelectedProductIds() {
        let ids = [];
        $('select[name^="products"]').each(function () {
            let val = $(this).val();
            if (val) ids.push(val);
        });
        return ids;
    }

    // 🎯 Function: Build dropdown options excluding selected IDs
    function buildProductOptions(excludeIds = []) {
        let options = '<option value="">Select Product</option>';
        allProducts.forEach(product => {
            if (!excludeIds.includes(product.id.toString())) {
                options += `<option value="${product.id}">${product.name}</option>`;
            }
        });
        return options;
    }

    // ➕ Add new product row
    $('#addRow').click(function () {
        const selected = getSelectedProductIds();
        const options = buildProductOptions(selected);

        let html = `
        <tr>
            <td>
                <select name="products[${row}][product_id]" class="form-control product-select">
                    ${options}
                </select>
            </td>
            <td><input type="number" name="products[${row}][quantity]" class="form-control qty"></td>
            <td><input type="number" step="0.01" name="products[${row}][unit_price]" class="form-control price"></td>
            <td><input type="text" class="form-control total" readonly></td>
            <td><button type="button" class="btn btn-sm btn-danger removeRow">X</button></td>
        </tr>`;
        $('#product_table tbody').append(html);
        row++;
    });

    // ❌ Remove row and refresh dropdowns
    $(document).on('click', '.removeRow', function () {
        $(this).closest('tr').remove();
        $('.product-select').trigger('change'); // Refresh dropdowns
    });

    // ✏️ Quantity × Price = Total
    $(document).on('input', '.qty, .price', function () {
        let tr = $(this).closest('tr');
        let qty = parseFloat(tr.find('.qty').val()) || 0;
        let price = parseFloat(tr.find('.price').val()) || 0;
        tr.find('.total').val((qty * price).toFixed(2));
        calculateSummary(); // optional if you're calculating due
    });

    // 🔁 When dropdown changes → refresh all other dropdowns
    $(document).on('change', '.product-select', function () {
        const selected = getSelectedProductIds();

        $('.product-select').each(function () {
            const current = $(this).val();
            const html = buildProductOptions(selected);

            if (current) {
                $(this).html(html + `<option value="${current}" selected hidden>${$(this).find("option:selected").text()}</option>`);
            } else {
                $(this).html(html);
            }
        });
    });
</script> --}}

<script>
    function calculateSummary() {
        let total = 0;

        // Step 1: টেবিল থেকে প্রতিটি প্রোডাক্টের total যোগ করি
        $('#product_table tbody tr').each(function () {
            let qty = parseFloat($(this).find('.qty').val()) || 0;
            let price = parseFloat($(this).find('.price').val()) || 0;
            total += qty * price;
        });

        // Step 2: Discount & VAT সংগ্রহ করি
        let discount = parseFloat($('input[name="discount"]').val()) || 0;
        let vatPercent = parseFloat($('input[name="vat_percent"]').val()) || 0;
        let paid = parseFloat($('input[name="paid_amount"]').val()) || 0;

        // Step 3: Calculate VAT & Due
        let afterDiscount = total - discount;
        let vatAmount = (vatPercent / 100) * afterDiscount;
        let grandTotal = afterDiscount + vatAmount;
        let dueAmount = grandTotal - paid;

        $('input[name="vat_amount"]').val(vatAmount.toFixed(2));
        $('input[name="total_amount"]').val(grandTotal.toFixed(2));
        $('input[name="due_amount"]').val(dueAmount.toFixed(2));
    }

    // When values change, recalculate
    $(document).on('input', '.qty, .price, input[name="discount"], input[name="vat_percent"], input[name="paid_amount"]', function () {
        calculateSummary();
    });

    // On initial page load
    $(document).ready(function () {
        calculateSummary();
    });
</script>
@endpush

