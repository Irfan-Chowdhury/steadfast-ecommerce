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
                    <form id="reportFilterForm" action="{{ route('report.generate') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="startDate">From Date</label>
                                <input type="date" class="form-control" id="startDate" name="start_date"
                                       value="{{ isset($startDate) ? date('Y-m-d', strtotime($startDate)) : date('Y-m-01') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="endDate">To Date</label>
                                <input type="date" class="form-control" id="endDate" name="end_date"
                                       value="{{ isset($endDate) ? date('Y-m-d', strtotime($endDate)) : date('Y-m-d') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="reportType">Report Type</label>
                                <select class="form-control" id="reportType" name="report_type">
                                    <option value="summary" {{ isset($reportType) && $reportType==='summary' ? 'selected' : ''  }}>Summary Report</option>
                                    <option value="detailed" {{ isset($reportType) && $reportType==='detailed' ? 'selected' : ''  }}>Detailed Report</option>
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
                                    <h2 id="totalSales">{{ isset($summaryReport) ? $summaryReport->totalSales : '0.00'}} TK</h2>
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
                                    <h2 id="totalExpenses">{{ isset($summaryReport) ? $summaryReport->totalExpenses : '0.00' }} TK</h2>
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
                                    <h2 id="netProfit">{{ isset($summaryReport) ? $summaryReport->totalDiscount : '0.00' }} TK</h2>
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
                                    <h2 id="netProfit">{{ isset($summaryReport) ? $summaryReport->totalVat : '0.00' }} TK</h2>
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
                                    <h2 id="netProfit">{{ isset($summaryReport) ? $summaryReport->netProfit : '0.00' }} TK</h2>
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

                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center" id="reportTable">
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
                                @if (!isset($detailsReport) || ((isset($reportType) && $reportType === 'summary') || $reportType === ''))
                                    <tr>
                                        <td colspan="7" class="text-center">Select date range and generate report</td>
                                    </tr>
                                @else
                                    @foreach ($detailsReport as $item)
                                        <tr class="text-center">
                                            <td>{{ $item->date }}</td>
                                            <td>{{ $item->invoiceNumber }}</td>
                                            <td>৳ {{ $item->sales }}</td>
                                            <td>৳ {{ $item->discount }}</td>
                                            <td>৳ {{ $item->vat }}</td>
                                            <td>৳ {{ $item->expenses }}</td>
                                            <td>৳ {{ $item->profit }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot class="font-weight-bold">
                                <tr>
                                    <td colspan="2">TOTAL</td>
                                    <td id="footerSales">৳ {{ isset($summaryReport) && isset($reportType) && $reportType ==='detailed'  ? $summaryReport->totalSales : '0.00'}}</td>
                                    <td id="footerDiscount">৳ {{ isset($summaryReport) && isset($reportType) && $reportType ==='detailed'  ? $summaryReport->totalDiscount : '0.00' }} </td>
                                    <td id="footerVat">৳ {{ isset($summaryReport) && isset($reportType) && $reportType ==='detailed'  ? $summaryReport->totalVat : '0.00' }}</td>
                                    <td id="footerExpenses">৳ {{ isset($summaryReport) && isset($reportType) && $reportType ==='detailed'  ? $summaryReport->totalExpenses : '0.00' }}</td>
                                    <td id="footerProfit">৳ {{ isset($summaryReport) && isset($reportType) && $reportType ==='detailed'  ? $summaryReport->netProfit : '0.00' }}</td>
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

    // Print report
    $('#printReport').click(function() {
        window.print();
    });


});
</script>
@endpush
