@extends('admin.layouts.master')

@section('title', 'Sales Form')

@section('admin_content')
<style>
    .card {
        border-radius: 0.5rem;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .table thead th {
        border-bottom: 2px solid #dee2e6;
    }
    .table tfoot td {
        border-top: 2px solid #dee2e6;
    }
    @media print {
        .card-header, .card-footer, .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
        }
    }
</style>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-3"><i class="fas fa-chart-line"></i> Financial Report</h2>

            <!-- Filter Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Report</h5>
                </div>
                <div class="card-body">
                    <form id="reportFilterForm" action="{{ route('financial.report.data') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="startDate">From Date</label>
                                <input type="date" class="form-control" id="startDate" name="start_date"
                                       value="{{ date('Y-m-01') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="endDate">To Date</label>
                                <input type="date" class="form-control" id="endDate" name="end_date"
                                       value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="reportType">Report Type</label>
                                <select class="form-control" id="reportType" name="report_type">
                                    <option value="summary">Summary Report</option>
                                    <option value="detailed">Detailed Report</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Generate Report
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-primary h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Total Sales</h6>
                                    <h2 id="totalSales">{{ isset($summary) ? $summary->totalSales : '0.00'}} TK</h2>
                                </div>
                                <i class="fas fa-shopping-cart fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-danger h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Total Expenses</h6>
                                    <h2 id="totalExpenses">{{ isset($summary) ? $summary->totalExpenses : '0.00' }} TK</h2>
                                </div>
                                <i class="fas fa-money-bill-wave fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Total Discount</h6>
                                    <h2 id="netProfit">{{ isset($summary) ? $summary->totalDiscount : '0.00' }} TK</h2>
                                </div>
                                <i class="fas fa-chart-pie fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-dark h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Total Vat</h6>
                                    <h2 id="netProfit">{{ isset($summary) ? $summary->totalVat : '0.00' }} TK</h2>
                                </div>
                                <i class="fas fa-chart-pie fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Net Profit</h6>
                                    <h2 id="netProfit">{{ isset($summary) ? $summary->netProfit : '0.00' }} TK</h2>
                                </div>
                                <i class="fas fa-chart-pie fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Data Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-table"></i> Report Data</h5>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary" id="printReport">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <button class="btn btn-sm btn-outline-primary" id="exportExcel">
                            <i class="fas fa-file-excel"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="reportTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice #</th>
                                    <th>Sales</th>
                                    <th>Discount</th>
                                    <th>VAT</th>
                                    <th>Expenses</th>
                                    <th>Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                                <tr>
                                    <td colspan="7" class="text-center">Select date range and generate report</td>
                                </tr>
                            </tbody>
                            <tfoot class="font-weight-bold">
                                <tr>
                                    <td colspan="2">TOTAL</td>
                                    <td id="footerSales">0.00</td>
                                    <td id="footerDiscount">0.00</td>
                                    <td id="footerVat">0.00</td>
                                    <td id="footerExpenses">0.00</td>
                                    <td id="footerProfit">0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Modal -->
<div class="modal fade" id="chartModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Financial Overview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <canvas id="financialChart" height="400"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection


@push('admin_scripts')
<script>


$(document).ready(function() {
    // Generate report on form submit
    // $('#reportFilterForm').submit(function(e) {
    //     e.preventDefault();
    //     loadReportData();
    // });

    // Load initial report data
    function loadReportData() {
        const formData = $('#reportFilterForm').serialize();

        $.ajax({
            url: "{{ route('financial.report.data') }}",
            type: "GET",
            data: formData,
            success: function(response) {
                updateSummaryCards(response.summary);
                populateReportTable(response.data);
                updateFooterTotals(response.summary);
            },
            error: function(xhr) {
                toastr.error('Error loading report data');
            }
        });
    }

    // Update summary cards
    function updateSummaryCards(summary) {
        $('#totalSales').text(summary.total_sales.toFixed(2) + ' TK');
        $('#totalExpenses').text(summary.total_expenses.toFixed(2) + ' TK');
        $('#netProfit').text(summary.net_profit.toFixed(2) + ' TK');
    }

    // Populate report table
    function populateReportTable(data) {
        const $tbody = $('#reportTable tbody');
        $tbody.empty();

        if (data.length === 0) {
            $tbody.append('<tr><td colspan="7" class="text-center">No data available for selected period</td></tr>');
            return;
        }

        data.forEach(item => {
            $tbody.append(`
                <tr>
                    <td>${item.date}</td>
                    <td>${item.invoice_number || 'N/A'}</td>
                    <td class="text-right">${item.sales.toFixed(2)}</td>
                    <td class="text-right">${item.discount.toFixed(2)}</td>
                    <td class="text-right">${item.vat.toFixed(2)}</td>
                    <td class="text-right">${item.expenses.toFixed(2)}</td>
                    <td class="text-right">${item.profit.toFixed(2)}</td>
                </tr>
            `);
        });
    }

    // Update footer totals
    function updateFooterTotals(summary) {
        $('#footerSales').text(summary.total_sales.toFixed(2));
        $('#footerDiscount').text(summary.total_discount.toFixed(2));
        $('#footerVat').text(summary.total_vat.toFixed(2));
        $('#footerExpenses').text(summary.total_expenses.toFixed(2));
        $('#footerProfit').text(summary.net_profit.toFixed(2));
    }

    // Print report
    $('#printReport').click(function() {
        window.print();
    });

    // Export to Excel
    $('#exportExcel').click(function() {
        // Implement Excel export functionality
        toastr.info('Excel export functionality will be implemented');
    });
});
</script>
@endpush
