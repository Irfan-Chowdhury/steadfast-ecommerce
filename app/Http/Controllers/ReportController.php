<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.pages.reports.index');
    }

    public function getReportData(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'report_type' => 'nullable|in:summary,detailed'
        ]);

        // Get sales data
        $sales = Sale::with('saleItems.product')
            ->whereBetween('date', [$validated['start_date'], $validated['end_date']])
            ->get();

        // Calculate totals
        $totalExpenses = 0;
        foreach ($sales as $sale) {
            foreach ($sale->saleItems as $saleItem) {
                $totalExpenses += $saleItem->quantity * $saleItem->product->purchase_price;
            }
        }

        $netProfit = $sales->sum('total_amount') - $totalExpenses - $sales->sum('discount');

        $summary = (object) [
            'totalSales' => number_format($sales->sum('total_amount'), 2),
            'totalDiscount' => number_format($sales->sum('discount'), 2),
            'totalVat' => number_format($sales->sum('vat_amount'), 2),
            'totalExpenses' => number_format($totalExpenses, 2),
            'netProfit' => number_format($netProfit, 2)
        ];


        return view('admin.pages.reports.index', compact('summary'));

    }
}
