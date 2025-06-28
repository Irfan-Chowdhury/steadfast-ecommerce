<?php

declare(strict_types=1);

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
        $validated = $this->validateRequest($request);

        $sales = $this->getSalesData($validated['start_date'], $validated['end_date']);
        $totalExpenses = $this->calculateTotalExpenses($sales);
        $netProfit = $this->calculateNetProfit($sales, $totalExpenses);

        $summaryReport = $this->buildSummary($sales, $totalExpenses, $netProfit);

        $detailsReport = [];
        if ($validated['report_type'] === 'detailed') {
            $detailsReport = $this->buildDetailedReport($sales);
        }

        return view('admin.pages.reports.index', [
            'startDate'     => $validated['start_date'],
            'endDate'       => $validated['end_date'],
            'reportType'    => $validated['report_type'],
            'summaryReport' => $summaryReport,
            'detailsReport' => $detailsReport,
        ]);
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'report_type'  => 'nullable|in:summary,detailed',
        ]);
    }

    private function getSalesData(string $startDate, string $endDate)
    {
        return Sale::with('saleItems.product')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
    }

    private function calculateTotalExpenses($sales): float
    {
        $totalExpenses = 0;
        foreach ($sales as $sale) {
            foreach ($sale->saleItems as $saleItem) {
                $totalExpenses += $saleItem->quantity * $saleItem->product->purchase_price;
            }
        }

        return $totalExpenses;
    }

    private function calculateNetProfit($sales, float $expenses): float
    {
        return $sales->sum('total_amount') - $expenses - $sales->sum('discount');
    }

    private function buildSummary($sales, float $expenses, float $netProfit): object
    {
        return (object) [
            'totalSales'     => number_format((float)$sales->sum('total_amount'),2),
            'totalDiscount'  => number_format((float)$sales->sum('discount'),2),
            'totalVat'       => number_format((float)$sales->sum('vat_amount'),2),
            'totalExpenses'  => number_format((float)$expenses,2),
            'netProfit'      => number_format((float)$netProfit,2),
        ];
    }

    private function buildDetailedReport($sales)
    {
        return $sales->map(function ($sale) {
            $expenses = $sale->saleItems->sum(function ($item) {
                return $item->quantity * $item->product->purchase_price;
            });

            return (object) [
                'date'          => $sale->date->format('Y-m-d'),
                'invoiceNumber' => $sale->invoice_number,
                'sales'         => number_format((float)$sale->total_amount, 2),
                'discount'      => number_format((float)$sale->discount, 2),
                'vat'           => number_format((float)$sale->vat_amount, 2),
                'expenses'      => number_format($expenses, 2),
                'profit'        => number_format((float)$sale->total_amount - $expenses - $sale->discount, 2),
            ];
        });
    }
}
